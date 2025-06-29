<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order; 
use App\Models\OrderItem; 
use App\Models\Transaction; 
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; 

class AdminController extends Controller
{
    protected function deleteProductImageFiles($imageName)
    {
        if ($imageName) {
            $mainPath = public_path('uploads/products') . '/' . $imageName;
            $thumbnailPath = public_path('uploads/products/thumbnails') . '/' . $imageName;

            if (File::exists($mainPath)) {
                File::delete($mainPath);
            }
            if (File::exists($thumbnailPath)) {
                File::delete($thumbnailPath);
            }
        }
    }

    protected function deleteBrandCategoryThumbnails($imageName, $type)
    {
        if ($imageName) {
            $path = public_path('uploads/' . $type) . '/' . $imageName;
            if (File::exists($path)) {
                File::delete($path);
            }
        }
    }

    public function index()
    {
        $orders = Order::orderBy('created_at', 'DESC')->get()->take(10);

        // Pastikan Anda memuat data dashboard di sini dan melewatkannya ke view
        $dashboardDatas = DB::select("SELECT
                                            SUM(total) AS TotalAmount,
                                            SUM(IF(status='ordered',total,0)) AS TotalOrderedAmount,
                                            SUM(IF(status='delivered',total,0)) AS TotalDeliveredAmount,
                                            SUM(IF(status='canceled',total,0)) AS TotalCanceledAmount,
                                            COUNT(*) AS Total,
                                            SUM(IF(status='ordered',1,0)) AS TotalOrdered,
                                            SUM(IF(status='delivered',1,0)) AS TotalDelivered,
                                            SUM(IF(status='canceled',1,0)) AS TotalCanceled
                                        FROM orders");

        $dashboardData = $dashboardDatas[0] ?? (object)[ // Default to empty object if query returns nothing
            'TotalAmount' => 0, 'TotalOrderedAmount' => 0, 'TotalDeliveredAmount' => 0, 'TotalCanceledAmount' => 0,
            'Total' => 0, 'TotalOrdered' => 0, 'TotalDelivered' => 0, 'TotalCanceled' => 0
        ];

        return view('admin.index', compact('orders', 'dashboardData'));
    }

    public function brands()
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);
        return view('admin.brands', compact('brands'));
    }

    public function add_brand()
    {
        return view('admin.brand-add');
    }

    public function brand_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'required|mimes:png,jpg,jpeg|max:2048'
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_name = Carbon::now()->timestamp . '.' . $image->extension();
            $this->GenerateBrandThumbnailsImage($image, $file_name);
            $brand->image = $file_name;
        }

        $brand->save();
        return redirect()->route('admin.brands')->with('status', 'Merek telah berhasil ditambahkan!');
    }

    public function brand_edit($id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return redirect()->route('admin.brands')->with('error', 'Merek tidak ditemukan!');
        }
        return view('admin.brand-edit', compact('brand'));
    }

    public function brand_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,' . $request->id,
            'image' => 'nullable|mimes:png,jpg,jpeg|max:2048'
        ]);

        $brand = Brand::find($request->id);
        if (!$brand) {
            return redirect()->route('admin.brands')->with('error', 'Merek tidak ditemukan!');
        }

        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $this->deleteBrandCategoryThumbnails($brand->image, 'brands');
            $image = $request->file('image');
            $file_name = Carbon::now()->timestamp . '.' . $image->extension();
            $this->GenerateBrandThumbnailsImage($image, $file_name);
            $brand->image = $file_name;
        } else if ($request->input('remove_image') == '1') {
            $this->deleteBrandCategoryThumbnails($brand->image, 'brands');
            $brand->image = null;
        }

        $brand->save();
        return redirect()->route('admin.brands')->with('status', 'Merek telah berhasil diperbarui!');
    }

    public function GenerateBrandThumbnailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/brands');
        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true, true);
        }
        $img = Image::read($image->path());
        $img->cover(124, 124, 'top')->save($destinationPath . '/' . $imageName);
    }

    public function brand_delete($id)
    {
        $brand = Brand::find($id);
        if ($brand) {
            $this->deleteBrandCategoryThumbnails($brand->image, 'brands');
            $brand->delete();
            return redirect()->route('admin.brands')->with('status', 'Merek telah berhasil dihapus!');
        }
        return redirect()->route('admin.brands')->with('error', 'Merek tidak ditemukan!');
    }

    public function categories()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function category_add()
    {
        return view('admin.category-add');
    }

    public function category_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_name = Carbon::now()->timestamp . '.' . $image->extension();
            $this->GenerateCategoryThumbailsImage($image, $file_name);
            $category->image = $file_name;
        }

        $category->save();
        return redirect()->route('admin.categories')->with('status', 'Kategori telah berhasil ditambahkan!');
    }

    public function GenerateCategoryThumbailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/categories');
        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true, true);
        }
        $img = Image::read($image->path());
        $img->cover(124, 124, 'top')->save($destinationPath . '/' . $imageName);
    }

    public function category_edit($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return redirect()->route('admin.categories')->with('error', 'Kategori tidak ditemukan!');
        }
        return view('admin.category-edit', compact('category'));
    }

    public function category_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $request->id,
            'image' => 'nullable|mimes:png,jpg,jpeg|max:2048'
        ]);

        $category = Category::find($request->id);
        if (!$category) {
            return redirect()->route('admin.categories')->with('error', 'Kategori tidak ditemukan!');
        }

        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $this->deleteBrandCategoryThumbnails($category->image, 'categories');
            $image = $request->file('image');
            $file_name = Carbon::now()->timestamp . '.' . $image->extension();
            $this->GenerateCategoryThumbailsImage($image, $file_name);
            $category->image = $file_name;
        } else if ($request->input('remove_image') == '1') {
            $this->deleteBrandCategoryThumbnails($category->image, 'categories');
            $category->image = null;
        }

        $category->save();
        return redirect()->route('admin.categories')->with('status', 'Kategori telah berhasil diperbarui!');
    }

    public function category_delete($id)
    {
        $category = Category::find($id);
        if ($category) {
            $this->deleteBrandCategoryThumbnails($category->image, 'categories');
            $category->delete();
            return redirect()->route('admin.categories')->with('status', 'Kategori telah berhasil dihapus!');
        }
        return redirect()->route('admin.categories')->with('error', 'Kategori tidak ditemukan!');
    }

    public function products()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.products', compact('products'));
    }

    public function product_add()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-add', compact('categories', 'brands'));
    }

    public function product_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg|max:2048',
            'images.*' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            'category_id' => 'required',
            'brand_id' => 'required'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_timestamp = Carbon::now()->timestamp;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailImage($image, $imageName);
            $product->image = $imageName;
        } else {
            $product->image = null;
        }

        $gallery_arr = [];
        $gallery_images_string = null;

        if ($request->hasFile('images')) {
            $files = $request->file('images');
            $counter = 1;
            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    $gextension = $file->extension();
                    $gfileName = $current_timestamp . "." . $counter . "." . $gextension;
                    $this->GenerateProductThumbnailImage($file, $gfileName);
                    $gallery_arr[] = $gfileName;
                    $counter++;
                }
            }
            if (!empty($gallery_arr)) {
                $gallery_images_string = implode(',', $gallery_arr);
            }
        }
        $product->images = $gallery_images_string;
        $product->save();
        return redirect()->route('admin.products')->with('status', 'Produk telah berhasil ditambahkan!');
    }

    public function GenerateProductThumbnailImage($image, $imageName)
    {
        $destinationPathThumbnail = public_path('uploads/products/thumbnails');
        $destinationPath = public_path('uploads/products');

        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true, true);
        }
        if (!File::isDirectory($destinationPathThumbnail)) {
            File::makeDirectory($destinationPathThumbnail, 0777, true, true);
        }

        $imgMain = Image::read($image->path());
        $imgMain->cover(540, 689, 'top')
                ->save($destinationPath . '/' . $imageName);

        $imgThumbnail = Image::read($image->path());
        $imgThumbnail->scale(104, 104)
                     ->save($destinationPathThumbnail . '/' . $imageName);
    }

    public function product_edit($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->route('admin.products')->with('error', 'Produk tidak ditemukan!');
        }
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-edit', compact('product', 'categories', 'brands'));
    }

    public function product_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug,' . $request->id,
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            'images.*' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            'category_id' => 'required',
            'brand_id' => 'required'
        ]);

        $product = Product::find($request->id);
        if (!$product) {
            return redirect()->route('admin.products')->with('error', 'Produk tidak ditemukan!');
        }

        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $current_timestamp = Carbon::now()->timestamp;

        if ($request->hasFile('image')) {
            $this->deleteProductImageFiles($product->image);
            $image = $request->file('image');
            $imageName = $current_timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailImage($image, $imageName);
            $product->image = $imageName;
        } else if ($request->input('remove_main_image') == '1') {
            $this->deleteProductImageFiles($product->image);
            $product->image = null;
        }

        $gallery_arr = [];
        $new_gallery_images_string = null;

        if ($product->images && !empty($product->images)) {
            foreach (explode(',', $product->images) as $old_image_name) {
                $this->deleteProductImageFiles($old_image_name);
            }
        }

        if ($request->hasFile('images')) {
            $files = $request->file('images');
            $counter = 1;
            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    $gextension = $file->extension();
                    $gfileName = $current_timestamp . "." . $counter . "." . $gextension;
                    $this->GenerateProductThumbnailImage($file, $gfileName);
                    $gallery_arr[] = $gfileName;
                    $counter++;
                }
            }
            if (!empty($gallery_arr)) {
                $new_gallery_images_string = implode(',', $gallery_arr);
            }
        }
        $product->images = $new_gallery_images_string;

        $product->save();
        return redirect()->route('admin.products')->with('status', 'Produk telah berhasil diperbarui!');
    }

    public function product_delete($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->route('admin.products')->with('error', 'Produk tidak ditemukan!');
        }

        $this->deleteProductImageFiles($product->image);

        if ($product->images && !empty($product->images)) {
            foreach (explode(',', $product->images) as $old_gallery_image) {
                $this->deleteProductImageFiles($old_gallery_image);
            }
        }

        $product->delete();
        return redirect()->route('admin.products')->with('status', 'Produk telah berhasil dihapus!');
    }

    public function orders()
    {
        $orders = Order::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.orders', compact('orders'));
    }

    public function order_details($order_id)
    {
        $order = Order::find($order_id);

        if (!$order) {
            return redirect()->route('admin.orders')->with('error', 'Pesanan tidak ditemukan!');
        }

        $orderItems = OrderItem::where('order_id', $order->id)->orderBy('id')->paginate(12);

        $transaction = Transaction::where('order_id', $order->id)->first();

        return view('admin.order-details', compact('order', 'orderItems', 'transaction'));
    }

    public function update_order_status(Request $request)
    {
        $order = Order::find($request->order_id);
        if (!$order) { 
            return back()->with('error', 'Pesanan tidak ditemukan!');
        }

        $order->status = $request->order_status;
        if ($request->order_status == 'delivered') {
            $order->delivered_date = Carbon::now();
        } elseif ($request->order_status == 'canceled') {
            $order->canceled_date = Carbon::now();
        }
        $order->save();

        if ($request->order_status == 'delivered') {
            $transaction = Transaction::where('order_id', $request->order_id)->first();
            if ($transaction) { 
                $transaction->status = 'approved';
                $transaction->save();
            } else {
                Log::warning('Transaksi tidak ditemukan untuk Order ID: ' . $request->order_id);
            }
        }
        return back()->with("status", "status berhasil diubah!");
    }
}