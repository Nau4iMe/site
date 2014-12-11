<?php
namespace admin;

use View;
use Ban;
use Redirect;
use Input;
use User;
use Sanitizer;

class BanController extends \BaseController {

    public function __construct()
    {
        $this->beforeFilter('csrf', array('on' => 'post'));
        $this->data['active'] = 'ban';
    }

    public function index()
    {
        $this->data['bans'] = Ban::leftJoin('smf_members as u', 'u.id_member', '=', 'ban.user_id')
            ->paginate(20, array('ban.id', 'ban.reason', 'u.member_name'));

        return View::make('admin.ban.index', $this->data);
    }

    public function destroy($id)
    {
        $ban = Ban::findOrFail($id);

        if ($ban) {
            $ban->delete();
            return Redirect::route('admin.ban.index')->with('global_success', 'Наказанието беше премахнато успешно!');
        }
        return Redirect::route('admin.ban.index')->with('global_error', 'Възникна грешка, моля опитайте отново!');
    }

    public function create()
    {
        return View::make('admin.ban.create', $this->data);
    }

    public function store()
    {
        $sanitize = new Sanitizer(Input::all());
        Input::merge($sanitize->get());

        $validation = new Ban();
        if ($validation->validate(Input::all())) {
            $validation->user_id = Input::get('user_id');
            $validation->reason = Input::get('reason');
            $validation->save();            
            
            return Redirect::route('admin.ban.index')
                ->with('global_success', 'Потребителят беше добавен в списъка със забранени.');
        }
        return Redirect::route('admin.ban.create')->with('global_error', 'Грешка, моля опитайте отново!');
    }

    /**
     * Searches for a user in the SMF usertable
     * @return a user (json-formatted)
     */
    public function findUser()
    {
        $sanitize = new Sanitizer(Input::only('search'));
        Input::merge($sanitize->get());
        $search = Input::get('search');

        if (strlen($search) > 0) {
            $user_id = User::getUserParam($search, 'id_member');
            if ($user_id) {
                $exists = Ban::where('user_id', '=', $user_id)->count();
                if (!$exists) {
                    $data['username'] = User::getUserParam($search, 'member_name');
                    $data['id'] = $user_id;
                    echo json_encode($data);
                }
            }
        }
    }

}
