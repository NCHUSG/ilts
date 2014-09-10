<?php

class API_ClientController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $project = new OAuthProject;
        $project->where('developer_id', '=', Session::get('user_being.u_id'));

        $client = new OAuthClient;
        $client->where('project_id', '=', $project->project_id);

        return Response::json($client->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('developer/client/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $this->beforeFilter('csrf', array('on' => 'post'));

        $rules      = Config::get('validation.API.client.store.rules');
        $messages   = Config::get('validation.API.client.store.messages');
        $validator  = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::action('API_ClientController@create')->withErrors($validator)->withInput();
        }
        else {
            $project = OAuthProject::find(Input::get('project_id'));

            if ( $project->developer_id != Session::get('user_being.u_id') ) {
                return '權限錯誤，請勿擅自更改project id的輸入值！';
            }

            $client = new OAuthClient;
            $client->client_key     = OAuthClient::generateKey(true);
            $client->client_secret  = OAuthClient::generateKey();
            $client->project_id     = Input::get('project_id');
            $client->from_uri       = Input::get('input_from_uri');
            $client->redirect_uri   = Input::get('input_redirect_uri');
            $client->save();
            return Redirect::to(route('dev') . '#projects');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $client = new OAuthClient;
        return Response::json($client->find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data['client'] = OAuthClient::find($id);
        return View::make('developer/client/edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $this->beforeFilter('csrf', array('on' => 'post'));

        $rules      = Config::get('validation.API.client.update.rules');
        $messages   = Config::get('validation.API.client.update.messages');
        $validator  = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            return Redirect::action('API_ClientController@edit', $id)->withErrors($validator)->withInput();
        }
        else {
            $client  = OAuthClient::find($id);
            $project = OAuthProject::find($client->project_id);

            if ( $project->developer_id != Session::get('user_being.u_id') ) {
                return '你沒有權限修改！';
            }

            $client->from_uri           = Input::get('from_uri');
            $client->redirect_uri       = Input::get('redirect_uri');
            $client->save();
            return Redirect::to(route('dev') . '#projects');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $client = OAuthClient::find($id);
        $project = OAuthProject::find($client->project_id);
        $response = array('project_id' => $client->project_id);

        if ( $project->developer_id != Session::get('user_being.u_id') ) {
            return '你沒有權限修改！';
        }

        $client->delete();

        return Response::json($response);
    }

}
