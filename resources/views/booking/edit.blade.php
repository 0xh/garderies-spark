@extends('layouts.two-columns')

@section('title', 'Editer')

@section('content')
    <booking-edit inline-template>
        <div class="card card-default">
            <div class="card-header">Editer remplacement</div>
            <div class="card-body">
                <form action="{{route('bookings.update', [$booking->id])}}" method="post">
                    {{csrf_field()}}
                    {{method_field('PUT')}}
                    <div class="row">
                        <div class="form-group col">
                            <label for="name">Début :</label>
                            <flat-pickr
                                    :config="flatPickrConfig"
                                    value="{{$booking->start->format('d.m.Y H:i')}}"
                                    class="form-control"
                                    placeholder="Select a date"
                                    name="date_start">
                            </flat-pickr>
                        </div>
                        <div class="form-group col">
                            <label for="name">Fin :</label>
                            <flat-pickr
                                    :config="flatPickrConfig"
                                    value="{{$booking->end->format('d.m.Y H:i')}}"
                                    class="form-control"
                                    placeholder="Select a date"
                                    name="date_end">
                            </flat-pickr>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Enregistrer</button>
                </form>
            </div>
        </div>
    </booking-edit>
@endsection

