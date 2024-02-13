<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Contact;
use App\Models\User;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('name')->get();
        $clients = Client::orderBy('rz')->get();
        $events = [];

        $clientes = Client::get();
        $contacts = Contact::with(['client','user'])->get();
        
        foreach ($contacts as $contact) {
            $events[] = [
                'id'        => $contact->id,
                'start'     => $contact->start,
                'end'       => $contact->end,
                'client'    => $contact->client_id,
                'comments'  => $contact->comments,
                'title'     => $contact->client->rz . ' - '.$contact->user->name,
            ];
        }
        
        return view('calendar.index', compact('events','clients','users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();        
        $data['user_id'] = Auth()->user()->id;
        if($data['id'] == NULL){
            $save = Contact::create($data);
        }else{
            $contact = Contact::find($data['id']);
            $save = $contact->update($data);
        }
        if($save){
            return redirect()->back()->withInput();
        }
        else{
            dd('deu ruim');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
