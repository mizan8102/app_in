<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Models\UserMenu\UserMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menu=[];
        // if(intval(Auth::user()->id) == 344){
        //     $menu=UserMenu::select('vms_menu.id','vms_menu.menu_name as name','icon_name as icon','route_name as href','menu_parent_id')->whereIn('vms_menu.id',[500,308,900,928])->get();
        // }else{

            $menu=UserMenu::select('vms_menu.id','vms_menu.menu_name as name','icon_name as icon','route_name as href','menu_parent_id')
           ->leftJoin('cs_users_menus','cs_users_menus.menu_id','vms_menu.id')
        //    ->join('cs_users_roles','cs_users_menus.user_id','cs_users_roles.users_id')
           ->where('cs_users_menus.user_id',Auth::user()->id)->groupBy('vms_menu.id')->get();
            // $menu=UserMenu::select('vms_menu.id','vms_menu.menu_name as name','icon_name as icon','route_name as href','menu_parent_id')->get();
        // }
        // $menu=UserMenu::select('vms_menu.id','vms_menu.menu_name as name','icon_name as icon','route_name as href','menu_parent_id')
//            ->join('cs_users_menus','cs_users_menus.menu_id','vms_menu.id')
//            ->join('cs_users_roles','cs_users_menus.user_id','cs_users_roles.users_id')
//            ->where('cs_users_roles.users_id',Auth::user()->id)

/*
       $menu=UserMenu::select('vms_menu.id','vms_menu.menu_name as name','icon_name as icon','route_name as href','menu_parent_id')
           ->join('cs_users_menus','cs_users_menus.menu_id','vms_menu.id')
        //    ->join('cs_users_roles','cs_users_menus.user_id','cs_users_roles.users_id')
           ->where('cs_users_menus.user_id',Auth::user()->id)
           
*/
            // ->get();
        $result=array();
        $i=0;
        foreach($menu as $item) {
            $result[$i]['id']=$item->id;
            $result[$i]['name']=$item->name;
            $result[$i]['icon']=[
                "class"=>$item->icon,
                "text" =>$item->name
            ];
            if(  $item->href !== null  ) {
                $result[$i]['href']= $item->href;
            }
            $result[$i]['menu_parent_id']=$item->menu_parent_id;
           $i++;
        }
        $organizedData = $this->organizeData($result);
        return $organizedData;
    }
    public function organizeData($data, $parentId = null) {
        $result = array();
        foreach ($data as $item) {
            if ($item['menu_parent_id'] == $parentId) {
                $children = $this->organizeData($data, $item['id']);
                if ($children) {
                    $item['children'] = $children;
                }
                $result[] = $item;
            }
        }
        return $result;
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
        //
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
