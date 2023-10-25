@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Product</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Create New Product</h4>

            </div>
            <div class="card-body">
                <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group row mb-4">
                        <div class="col-sm-12 col-md-7">
                            <div id="image-preview" class="image-preview">
                                <label for="image-upload" id="image-label">Choose File</label>
                                <input type="file" name="image" id="image-upload" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <select id="" class="form-control select2" name="category">
                            <option value="">Select</option>
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Price</label>
                        <input type="text" class="form-control" name="price" value="{{ old('price') }}">
                    </div>
                    <div class="form-group">
                        <label>Offer Price</label>
                        <input type="text" class="form-control" name="offer_price" value="{{ old('offer_price') }}">
                    </div>

                    <div class="form-group">
                        <label>Short Description</label>
                        <textarea name="short_description" class="form-control" value="{{ old('short_description') }}"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Long Description</label>
                        <textarea name="long_description" class="form-control summernote" value="{{ old('long_description') }}"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Sku</label>
                        <input type="text" class="form-control" name="sku" value="{{ old('sku') }}">
                    </div>

                    <div class="form-group">
                        <label>Seo Title</label>
                        <input type="text" class="form-control" name="seo_title" value="{{ old('seo_title') }}">
                    </div>

                    <div class="form-group">
                        <label>Seo Description</label>
                        <textarea name="seo_description" class="form-control" value="{{ old('seo_description') }}"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Show At Home</label>
                        <select id="" class="form-control" name="show_at_home">
                            <option value="1">Yes</option>
                            <option selected value="0">No</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select id="" class="form-control" name="status">
                            <option value="1">Active</option>
                            <option value="0">InActive</option>
                        </select>
                    </div>


                    <button class="btn btn-primary" type="submit">Create</button>

                </form>
            </div>
        </div>

    </section>
@endsection
