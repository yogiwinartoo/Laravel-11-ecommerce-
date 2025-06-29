@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Tambah produk</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}"> 
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="{{route('admin.products')}}">
                        <div class="text-tiny">produk</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Tambah Produk</div>
                </li>
            </ul>
        </div>
        <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data" action="{{route('admin.product-store')}}"> {{-- PERBAIKAN 1 --}}
            @csrf
            <div class="wg-box">
                <fieldset class="name">
                    <div class="body-title mb-10">Nama Produk <span class="tf-color-1">*</span>
                    </div>
                    <input class="mb-10" type="text" placeholder="Enter product name" name="name" tabindex="0" value="{{old('name')}}" aria-required="true" required="">
                    <div class="text-tiny">Jangan melebihi 100 karakter saat memasukkan nama produk.</div>
                </fieldset>
                @error('name') <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                <fieldset class="name">
                    <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Enter product slug" name="slug" tabindex="0" value="{{old('slug')}}" aria-required="true" required="">
                    <div class="text-tiny">Jangan melebihi 100 karakter saat memasukkan nama produk.</div>
                </fieldset>
                @error('slug') <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                <div class="gap22 cols">
                    <fieldset class="category">
                        <div class="body-title mb-10">Kategori <span class="tf-color-1">*</span>
                        </div>
                        <div class="select">
                            <select class="" name="category_id">
                                <option>Pilih Kategori</option>
                                @foreach ($categories as $category)
                                <option value="{{$category->id}}" @if(old('category_id') == $category->id) selected @endif>{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                    @error('category_id') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                    <fieldset class="brand">
                        <div class="body-title mb-10">Merek <span class="tf-color-1">*</span>
                        </div>
                        <div class="select">
                            <select class="" name="brand_id">
                                <option>Pilih Merek</option>
                                @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" @if(old('brand_id') == $brand->id) selected @endif>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                    @error('brand_id') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                </div>

                <fieldset class="shortdescription">
                    <div class="body-title mb-10">Deskripsi Singkat <span
                            class="tf-color-1">*</span></div>
                    <textarea class="mb-10 ht-150" name="short_description" placeholder="Short Description" tabindex="0" aria-required="true" required="">{{old('short_description')}}</textarea>
                    <div class="text-tiny">Jangan melebihi 100 karakter saat memasukkan nama produk.</div>
                </fieldset>
                @error('short_description') <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                <fieldset class="description">
                    <div class="body-title mb-10">Deskripsi <span class="tf-color-1">*</span>
                    </div>
                    <textarea class="mb-10" name="description" placeholder="Description" tabindex="0" aria-required="true" required="">{{old('description')}}</textarea>
                    <div class="text-tiny">Jangan melebihi 100 karakter saat memasukkan nama produk.</div>
                </fieldset>
                @error('description') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
            </div>
            <div class="wg-box">
                <fieldset>
                    <div class="body-title">Unggah Gambar <span class="tf-color-1">*</span>
                    </div>
                    <div class="upload-image flex-grow">
                        <div class="item" id="imgpreview" style="display:none">
                            <img src="" 
                                class="effect8" alt="">
                        </div>
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Jatuhkan gambar Anda di sini atau pilih <span
                                            class="tf-color">Klik untuk menjelajah</span></span>
                                <input type="file" id="myFile" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('image') <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                <fieldset>
                    <div class="body-title mb-10">Unggah Gambar digaleri</div>
                    <div class="upload-image mb-16">
                        <div id="galUpload" class="item up-load">
                            <label class="uploadfile" for="gFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="text-tiny">Jatuhkan gambar Anda di sini atau pilih <span
                                            class="tf-color">Klik untuk menjelajah</span></span>
                                <input type="file" id="gFile" name="images[]" accept="image/*" multiple="">
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('images') <span class="alert alert-danger text-center">{{$message}}</span> @enderror

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Harga Normal <span
                                class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter regular price" name="regular_price" tabindex="0" value="{{old('regular_price')}}" aria-required="true"required="">
                    </fieldset>
                    @error('regular_price') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                    <fieldset class="name">
                        <div class="body-title mb-10">Harga Promo <span
                                class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter sale price" name="sale_price" tabindex="0" value="{{old('sale_price')}}" aria-required="true" required="">
                    </fieldset>
                    @error('sale_price') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">SKU <span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="text" placeholder="Enter SKU" name="SKU" tabindex="0" value="{{old('SKU')}}" aria-required="true" required="">
                    </fieldset>
                    @error('SKU') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                    <fieldset class="name">
                        <div class="body-title mb-10">Kuantitas<span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="text" placeholder="Enter quantity" name="quantity" tabindex="0" value="{{old('quantity')}}" aria-required="true" required="">
                    </fieldset>
                    @error('quantity') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Stok</div>
                        <div class="select mb-10">
                            <select class="" name="stock_status">
                                <option value="instock" @if(old('stock_status') == 'instock') selected @endif>InStock</option>
                                <option value="outofstock" @if(old('stock_status') == 'outofstock') selected @endif>Out of Stock</option>
                            </select>
                        </div>
                    </fieldset>
                    @error('stock_status') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                    <fieldset class="name">
                        <div class="body-title mb-10">Unggulan</div>
                        <div class="select mb-10">
                            <select class="" name="featured">
                                <option value="0" @if(old('featured') == '0') selected @endif>Tidak</option>
                                <option value="1" @if(old('featured') == '1') selected @endif>ya</option>
                            </select>
                        </div>
                    </fieldset>
                    @error('featured') <span class="alert alert-danger text-center">{{$message}}</span> @enderror
                </div>
                <div class="cols gap10">
                    <button class="tf-button w-full" type="submit">Tambah Produk</button>
                </div>
            </div>
        </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function(){
        $("#myFile").on("change", function(e){
            const photoInp = $("#myFile");
            const [file] = this.files;
            if(file){
                $("#imgpreview img").attr("src",URL.createObjectURL(file));
                $("#imgpreview").show();
            }
        });

        $("#gFile").on("change", function(e){
            const photoInp = $("#gFile");
            const gphotos = this.files;
            $.each(gphotos, function(key,val){
                $("#galUpload").prepend(`<div class="item gitems"><img src="${URL.createObjectURL(val)}"/></div>`);
            });
        });

        $("input[name='name']").on("change", function(){
            $("input[name='slug']").val(StringToSlug($(this).val()));
        });
    });

    function StringToSlug(Text)
    {
    return Text.toLowerCase()
        .replace(/[^\w ]+/g, "")
        .replace(/ +/g, "-");
    }
</script>
@endpush