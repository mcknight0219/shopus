@extends('layouts.master')

@section('content')
    @if( Auth::user() )
        <div class="pure-g">
            <div class="pure-u-1-6"></div>
            <div class="pure-u-1 pure-u-sm-2-3">
                <div class="pure-u-1">
                    <div class="left headtitle">My Profile</div>
                </div>

                @include('shared.profile', ['profile'=> $profile])

                <div class="margintop2">
                    <div class="pure-u-1">
                        <div class="left headtitle">Products</div>
                        <div class="right">
                            <i @click="cmsData.showAddProductModal = true" class="fa fa-plus controlwidget"></i>
                        </div>

                        <add-product :show.sync="cmsData.showAddProductModal"></add-product>
                    </div>
                </div>

                <div class="margintop2">
                    <div v-for="product in cmsData.products">
                        <product-cell :product.sync="product"></product-cell>
                    </div>
                </div>
            </div>
            <div class="pure-u-1-6"></div>
        </div>

    @else
{{-- Landing page here --}}
    @endif
@stop
@section('script')
        <script src="js/cms.js"></script>
@stop