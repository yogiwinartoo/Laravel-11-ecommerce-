@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Informasi Kategori</h3>
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
                    <a href="{{route('admin.categories')}}">
                        <div class="text-tiny">Kategori</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Kategori Baru</div>
                </li>
            </ul>
        </div>
        <!-- new-category -->
        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{route('admin.category-store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <fieldset class="name">
                    <div class="body-title">Nama Kategori <span class="tf-color-1">*</span></div> 
                    <input class="flex-grow" type="text" placeholder="Category name" name="name" tabindex="0" value="{{old('name')}}" aria-required="true" required="">
                </fieldset>
                @error('name') <span class="alert-danger text-center">{{$message}} </span> @enderror 
                <fieldset class="name">
                    <div class="body-title">Slug Kategori <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Category Slug" name="slug" tabindex="0" value="{{old('slug')}}" aria-required="true" required="">
                </fieldset>
                @error('slug') <span class="alert-danger text-center">{{$message}} </span> @enderror
                <fieldset>
                    <div class="body-title">Unggah Gambar <span class="tf-color-1">*</span>
                    </div>
                    <div class="upload-image flex-grow">
                        <div class="item" id="imgpreview" style="display:none">
                            <img src="upload-1.html" class="effect8" alt="">
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
                @error('image') <span class="alert-danger text-center">{{$message}} </span> @enderror

                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Simpan</button>
                </div>
            </form>
        </div>
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
                $("#imgpreview img").attr("src", URL.createObjectURL(file));
                $("#imgpreview").show();
            }
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
