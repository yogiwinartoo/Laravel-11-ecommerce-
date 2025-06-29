<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Log; // Uncomment this line if you want to use Log:: for debugging

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart', compact('items'));
    }

    public function add_to_cart(Request $request)
    {
        Cart::instance('cart')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
        return redirect()->back();
    }

    public function increase_cart_quantity($rowId)
    {
        $cart = Cart::instance('cart');
        $product = $cart->get($rowId);
        if ($product) {
            $qty = $product->qty + 1;
            $cart->update($rowId, $qty);
        }
        return redirect()->back();
    }

    public function decrease_cart_quantity($rowId)
    {
        $cart = Cart::instance('cart');
        $product = $cart->get($rowId);
        if ($product) {
            $qty = $product->qty - 1;
            if ($qty < 1) {
                $cart->remove($rowId);
            } else {
                $cart->update($rowId, $qty);
            }
        }
        return redirect()->back();
    }

    public function remove_item($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }

    public function empty_cart()
    {
        Cart::instance('cart')->destroy();
        return redirect()->back();
    }

    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Cart::instance('cart')->content()->count() == 0) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong. Silakan tambahkan item sebelum checkout.');
        }

        $address = Address::where('user_id', Auth::user()->id)->where('isdefault', 1)->first();
        return view('checkout', compact('address'));
    }

    public function place_an_order(Request $request)
    {
        if (Cart::instance('cart')->content()->count() == 0) {
            // Log::warning('Percobaan order dengan keranjang kosong saat place_an_order.');
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong. Tidak dapat membuat pesanan.');
        }

        $user_id = Auth::user()->id;
        $address = Address::where('user_id', $user_id)->where('isdefault', true)->first();

        if (!$address) {
            $request->validate([
                'name' => 'required|max:100',
                'phone' => 'required|numeric|digits:10',
                'zip' => 'required|numeric|digits:6',
                'state' => 'required',
                'city' => 'required',
                'address' => 'required',
                'locality' => 'required',
                'landmark' => 'nullable', // PERBAIKAN: landmark jadi nullable di validasi
                'country' => 'required|string',
            ]);

            $address = new Address();
            $address->name = $request->name;
            $address->phone = $request->phone;
            $address->zip = $request->zip;
            $address->state = $request->state;
            $address->city = $request->city;
            $address->address = $request->address;
            $address->locality = $request->locality;
            $address->landmark = $request->landmark;
            $address->country = 'Indonesia'; // Masih hardcoded
            $address->user_id = $user_id;
            $address->isdefault = true;
            $address->save();
        }

        // Panggil setAmountForCheckout sebelum membuat Order
        $this->setAmountForCheckout();

        // Ambil data checkout setelah fungsi setAmountForCheckout() dijalankan
        $checkout = Session::get('checkout');

        if (is_null($checkout)) {
            // Log::error('Sesi checkout adalah NULL setelah setAmountForCheckout()! Tidak dapat membuat pesanan.');
            return redirect()->route('cart.index')->with('error', 'Gagal membuat pesanan. Sesi checkout tidak tersedia.');
        }

        $order = new Order();
        $order->user_id = $user_id;
        // PERBAIKAN: Pastikan nilai yang disimpan ke DB adalah angka murni
        $order->subtotal = floatval(str_replace(',', '', $checkout['subtotal'])); // Sanitasi jika ada koma/Rp
        $order->discount = floatval(str_replace(',', '', $checkout['discount']));
        $order->tax = floatval(str_replace(',', '', $checkout['tax']));
        $order->total = floatval(str_replace(',', '', $checkout['total']));
        
        $order->name = $address->name;
        $order->phone = $address->phone; // PERBAIKAN: Hapus baris duplikat phone di bawah
        $order->locality = $address->locality;
        $order->address = $address->address;
        $order->city = $address->city;
        $order->state = $address->state;
        $order->country = $address->country;
        $order->landmark = $address->landmark;
        $order->zip = $address->zip;
        $order->save();

        foreach (Cart::instance('cart')->content() as $item) {
            $orderItem = new OrderItem();
            $orderItem->product_id = $item->id;
            $orderItem->order_id = $order->id;
            $orderItem->price = floatval(str_replace(',', '', $item->price)); // PERBAIKAN: Sanitasi harga item
            $orderItem->quantity = $item->qty;
            $orderItem->save();
        }

        // Pembuatan transaksi dipindahkan ke luar kondisi if/elseif
        try {
            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->order_id = $order->id;
            // Pastikan $request->mode memiliki nilai yang valid untuk kolom enum 'mode'
            $validModes = ['cod', 'card', 'paypal']; // Sesuaikan dengan enum di migrasi transactions
            $transaction->mode = in_array($request->mode, $validModes) ? $request->mode : 'cod'; // Default ke 'cod' jika tidak valid
            $transaction->status = "pending";
            $transaction->save();
        } catch (\Exception $e) {
            // Log::error("Gagal menyimpan transaksi: " . $e->getMessage());
            // Optional: Tangani error, misal redirect kembali dengan pesan error
            return redirect()->back()->with('error', 'Gagal memproses transaksi order.');
        }


        // Logika spesifik mode pembayaran (kosongkan jika tidak ada integrasi gateway)
        if ($request->mode == "card") {
            // Tempatkan logika untuk memproses pembayaran kartu (redirect ke payment gateway)
        } elseif ($request->mode == "paypal") {
            // Tempatkan logika untuk memproses pembayaran PayPal (redirect ke PayPal)
        }

        Cart::instance('cart')->destroy();
        Session::forget('checkout');
        Session::put('order_id', $order->id);

        // Redirect ke route agar method order_confirmation() terpanggil
        return redirect()->route('cart.order-confirmation');
    }

    // Fungsi setAmountForCheckout() disederhanakan tanpa fitur kupon
    public function setAmountForCheckout()
    {
        $cart = Cart::instance('cart');

        if ($cart->content()->count() == 0) {
            Session::forget('checkout');
            return;
        }

        Session::put('checkout', [
            'discount' => 0, // Always 0 as no coupon feature
            'subtotal' => floatval(str_replace(',', '', $cart->subtotal())), // PERBAIKAN: Sanitasi
            'tax' => floatval(str_replace(',', '', $cart->tax())),         // PERBAIKAN: Sanitasi
            'total' => floatval(str_replace(',', '', $cart->total())),       // PERBAIKAN: Sanitasi
        ]);
    }

    public function order_confirmation()
    {
        if (Session::has('order_id')) {
            $order = Order::find(Session::get('order_id'));
            if (!$order) {
                // Log::error('Order ID di sesi tidak ditemukan di database. ID: ' . Session::get('order_id'));
                Session::forget('order_id');
                return redirect()->route('cart.index')->with('error', 'Pesanan tidak ditemukan untuk konfirmasi.');
            }
            return view('cart.order-confirmation', compact('order'));
        }
        return redirect()->route('cart.index');
    }
}