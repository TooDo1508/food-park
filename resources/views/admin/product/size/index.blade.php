@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Product Size ({{ $product->name }})</h1>
        </div>
        <div>
            <a href="{{ route('admin.product.index') }}" class="btn btn-primary mb-4">Go Back</a>
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h4>Create Size</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.product-size.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" class="form-control" name="name">
                                <input type="hidden" class="form-control" value="{{ $product->id }}" name="product_id">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Price</label>
                                <input type="text" class="form-control" name="price">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>All Image</h4>
            </div>
            {{-- <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($images as $image)
                        <tr>
                            <td><img src="{{ $image->image }}" width="150px"></td>
                            <td> <a href="{{ route('admin.product-gallery.destroy', $image->id) }}" class='btn btn-danger ml-2 delete-item'><i class='fas fa-trash'></i></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> --}}
        </div>
    </section>
@endsection
