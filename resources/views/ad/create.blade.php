@extends('layouts.two-columns')

@section('title', 'Création annonce')

@section('content')
    <ad-create inline-template>
        <div class="card card-default">
            <div class="card-header bg-dark text-white">{{$nursery->name}} - Ajout d'une annonce</div>
            <div class="card-body">
                <form action="{{route('ads.store', $nursery)}}" method="post">
                    {{csrf_field()}}
                    {{method_field('POST')}}

                    <input type="hidden" name="nursery" value="{{$nursery->id}}">
                    <div class="form-group">
                        <label for="name">Titre :</label>
                        <input type="text" class="form-control" name="title">
                    </div>
                    <div class="form-group">
                        <label for="description">Contenu :</label>
                        <editor name="description" plugins="lists" :init="{menubar:false, toolbar:'italic bold alignleft aligncenter alignright bullist numlist removeformat', branding: false}"></editor>
                    </div>

                    <button class="btn btn-primary float-right" type="submit">Enregistrer</button>
                </form>
            </div>
        </div>
    </ad-create>
@endsection

@section('nav-lateral')
    @include('network.nav')
@endsection

@section('scripts-head')
    <script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.8.2/tinymce.min.js"></script>
@endsection