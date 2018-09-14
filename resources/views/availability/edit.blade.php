@extends('layouts.two-columns')

@section('title', 'Edit')

@section('content')
    <availability-edit inline-template>
        <div class="card card-default">
            <div class="card-header">Edit availability</div>
            <div class="card-body">
                <form action="{{route('availabilities.update', [$availability->id])}}" method="post">
                    {{csrf_field()}}
                    {{method_field('PUT')}}
                    <div class="row">
                        <div class="form-group col">
                            <label for="name">Start:</label>
                            <flat-pickr
                                    v-model="date_start"
                                    :config="flatPickrConfig"
                                    value="{{$availability->start->format('d.m.Y H:i')}}"
                                    class="form-control"
                                    placeholder="Select a date"
                                    name="date_start">
                            </flat-pickr>
                        </div>
                        <div class="form-group col">
                            <label for="name">End:</label>
                            <flat-pickr
                                    v-model="date_end"
                                    :config="flatPickrConfig"
                                    value="{{$availability->end->format('d.m.Y H:i')}}"
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
    </availability-edit>
@endsection

