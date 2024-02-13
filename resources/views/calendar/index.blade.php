@extends('adminlte::page')

@section('title', 'Calendar')

@section('content_header')
    <div class="row">
        
    </div>    
@stop

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <h4>Agenda</h4>
                        </div>
                        <div class="col-12 col-md-6 text-right">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#createModal">
                                <i class="fas fa-plus"></i> Agendar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade bd-example-modal-xl" id="createModal" name="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                
                {{-- header --}}
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Novo Compromisso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                {{-- body --}}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            {{-- formulário --}}
                            <form id="myform" action="{{ route('calendar.store') }}" method="post">
                                @csrf
                                <input type="hidden" id="id" name="id">
                                <div class="form-row">
                                    <div class="form-group col-12 col-lg-10">
                                        <label for="client_id">* Cliente</label>
                                        <select name="client_id" id="client_id" class="form-control form-control-sm @error('client_id') is-invalid @enderror">
                                            <option value="">Selecione</option>
                                                @foreach($clients as $client)                           
                                                    <option value="{{$client->id}}"
                                                        @if( old('client_id') == $client->id)
                                                            selected
                                                        @endif
                                                        @if( isset($contact->client_id) && ($contact->client_id == $client->id)) 
                                                            selected 
                                                        @endif
                                                    >{{ $client->rz }}
                                                    </option>                               
                                                @endforeach
                                        </select>
                                        @error('client_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>{{-- end form row --}}
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="comments">* Observação</label>
                                        <textarea class="form-control @error('comments') is-invalid @enderror" id="comments" name="comments" rows="5" minlength="10" maxlength="2000">{{ $contact->comments ?? old('comments')}}</textarea>
                                        @error('comments')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>{{-- end form-row --}}
                                <div class="form-row">
                                    <div class="form-group col-6 col-lg-4">
                                        <label for="start">Início:</label>
                                        <input class="form-control @error('start') is-invalid @enderror" type="datetime-local" id="start" name="start">
                                        @error('start')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-6 col-lg-4">
                                        <label for="end">Fim:</label>
                                        <input class="form-control @error('end') is-invalid @enderror" type="datetime-local" id="end" name="end">
                                        @error('end')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- end modal body --}}

                {{-- footer --}}
                <div class="modal-footer">
                    <button type="button" id="btn-delete" class="btn btn-sm btn-danger hidden"><i class="fas fa-trash-alt"></i> Excluir</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                    <button type="submit" form="myform" class="btn btn-sm btn-primary"><i class="far fa-save"></i> Salvar</button>
                </div>
                {{-- end modal footer --}}
            </div>
        </div>
    </div>
    
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid/main.css" rel="stylesheet" />   
    <link rel="stylesheet" href="{{ asset('css/fullcalendar.css') }}"> 
@stop

@section('js')    
    <script src="{{url('js/fullcalendar.js')}}"></script>
    <script src="{{url('js/pt-br-fullcalendar.js')}}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, { 
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                timeZone: 'UTC',
                locale: 'pt-br',
                initialView: 'timeGridWeek',
                slotMinTime: '7:00:00',
                slotMaxTime: '20:00:00',
                navLinks:true,
                selectable:true,
                selectMirror:true,
                editable: true,
                dayMaxEvents:true,
                dateClick: function(info) {
                    abrirModal(info);
                },
                eventClick:function(info){
                    abrirModalEditar(info);
                },
                eventDrop:function(info){
                    console.log(info);
                },
                // eventResize:function(info){
                //     console.log(info);
                // },
                events: @json($events),
            });
            calendar.render();

            function abrirModal(info){
                let time = info.dateStr;
                if(time.length == 20){
                    document.querySelector('#start').value = info.dateStr.substr(0, 10) + 'T' + info.dateStr.substr(11, 5);
                }else{
                    document.querySelector('#start').value = info.dateStr.substr(0, 10) + 'T' + '08:00';
                    document.querySelector('#end').value = info.dateStr.substr(0, 10) + 'T' + '08:15';
                }
                $('#createModal').modal('show'); 
            };

            function abrirModalEditar(info){            
                $('#createModal').modal('show');
                document.querySelector('.modal-title').innerHTML = "Editando Compromisso";
                document.querySelector('#id').value = info.event.id;
                let client = document.querySelector('#client_id').selectedIndex = info.event.extendedProps.client;
                let start = document.querySelector('#start').value = info.event.startStr;
                let end = document.querySelector('#end').value = info.event.endStr;
                document.querySelector('#start').value = start.substr(0, 10) + 'T' + start.substr(11, 5);
                document.querySelector('#end').value = end.substr(0, 10) + 'T' + end.substr(11, 5);
                document.querySelector('#comments').value = info.event.extendedProps.comments;
                $('#btn-delete').show();
            };

            document.querySelector('#myform').addEventListener('submit',function(event){
                event.preventDefault();
                let client = document.querySelector('#client_id').selectedIndex;
                let comments = document.querySelector('#comments');
                let start = document.querySelector('#start');
                let end = document.querySelector('#end');

                if(client == 0){
                    document.getElementById('client_id').style.border = '#F00 solid 2px';
                    document.getElementById('client_id').focus();
                    return false;
                }

                if(comments.value == ''){
                    comments.style.border = '#F00 solid 2px';
                    comments.focus();
                    return false;
                }

                if(start.value == ''){
                    start.style.border = '#F00 solid 2px';
                    start.focus();
                    return false;
                }

                if(end.value == ''){
                    end.style.border = '#F00 solid 2px';
                    end.focus();
                    return false;
                }
                this.submit();
            });
            $('#createModal').on('hidden.bs.modal', function (e) {
                document.querySelector('.modal-title').innerHTML = "Novo Compromisso";
                document.querySelector('#id').value = '';
                document.querySelector('#comments').value = '';
                document.querySelector('#start').value = '';
                document.querySelector('#end').value = '';
                let client = document.querySelector('#client_id').selectedIndex = 0;
                $('#btn-delete').hide();
            });
            document.querySelector('.btn-danger').addEventListener('click', function () {
                if (confirm('Você confirma a exclusão do evento? Esta ação não pode ser desfeita.')) {
                    document.querySelector('#action').value = 'delete';
                    form_add_event.submit();
                    return true;
                }
                return false;
            });
        });        
    </script>
@stop