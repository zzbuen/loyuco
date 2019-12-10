<?php
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Manager\DashboardController@index');
Route::get('managermain', 'ViewController@getmain')->name('manager.main');
Route::get('main', 'ViewController@agentMain')->name('agent.main');
//Route::get('main', ' Agent\userManagerController@getUser')->name('agent.main');
Route::get('gamePeriod', 'ViewController@game_period');
Route::get('test', 'ViewController@test');
Route::get('download', 'ViewController@download');

/*时时彩*/
$router->any('shishicai', 'ZoushituController@shishicai');
/*11选5*/
$router->any('xuan5', 'ZoushituController@xuan5');
/*快三*/
$router->any('kuaisan', 'ZoushituController@kuaisan');
/*福彩3D*/
$router->any('fucai3d', 'ZoushituController@fucai3d');
/*PK10*/
$router->any('pk10', 'ZoushituController@pk10');
/*六合彩*/
$router->any('liuhecai', 'ZoushituController@liuhecai');
/*PC蛋蛋*/
$router->any('pcdd', 'ZoushituController@pcdd');
/*北京快乐8*/
$router->any('beijingkl8', 'ZoushituController@beijingkl8');

$router->any('xiufu', 'Manager\userManagerController@xiufu')->name('manager.xiufu');

Route::group(['prefix' => 'manager','namespace' => 'Manager'],function ($router)
{
    $router->get('/', 'DashboardController@index')->name('manager.index');
    $router->get('dash', 'DashboardController@index')->name('manager.dash');
    $router->get('login', 'LoginController@showLoginForm')->name('manager.login');
    $router->post('login', 'LoginController@login');
    $router->any('secure', 'LoginController@secure');
    $router->any('logout', 'LoginController@logout');
    $router->get('captcha/{tmp}', 'CaptchaController@captcha');
    $router->any('cscs', 'userManagerController@cs');
    $router->any('asd', 'userManagerController@asd');  //谷歌验证器

});


Route::group(['prefix' => 'manager', 'middleware' => 'auth.manager:manager', 'namespace' => 'Manager'], function ($router)
{
    $router->any('goole', 'userManagerController@goole');
    $router->any('zoushitu', 'OddsController@zoushitu');
    $router->any('game.configList.index', 'GameController@configList')->name('manager.game.configList.index');
    $router->any('game.config', 'GameController@config')->name('manager.game.config');
    $router->any('createGoods', 'GameController@createGoods')->name('manager.game.createGoods');
    $router->any('game.modify', 'GameController@modifyStatus')->name('manager.game.modify');
    $router->any('game.unResup', 'GameController@unResup')->name('manager.game.unResup'); /*取消预设*/

    $router->any('draw.index', 'GameController@drawFailList')->name('manager.draw.index');
    $router->any('draw.fixed', 'GameController@drawFixed')->name('manager.draw.fixed');

    $router->any("sum_gongzi.index","WagerecordController@index")->name("manager.sum_gongzi.index");
    $router->any("sum_fenhon.index","BonusrecordController@index")->name("manager.sum_fenhon.index");

    /*开奖结果*/
    $router->any('draw.kaijiang', 'DrawresultController@index')->name('manager.kaijiang.index');
    /*手动开奖*/
    $router->any('draw.chongqing', 'DrawresultController@draw_chongqing')->name('manager.draw.chongqing');
    /*11选5*/
    $router->any('draw.xuan5', 'DrawresultController@draw_xuan5')->name('manager.draw.xuan5');
    $router->post('draw.chongqing_ajax', 'DrawresultController@chongqing_ajax')->name('manager.draw.chongqing_ajax');
    $router->any('draw.look_people', 'DrawresultController@look_people')->name('manager.draw.look_people');

    $router->any('odds.index', 'OddsController@Odds')->name('manager.odds.index');
    $router->any('mondify_ajax', 'OddsController@mondify_ajax')->name('manager.mondify_ajax');
    $router->any('mondify_odds_ajax', 'OddsController@mondify_odds_ajax')->name('manager.mondify_odds_ajax');
    $router->any('modify_odd', 'OddsController@modify_odd')->name('manager.modify_odd');
    $router->any('modify_odds', 'OddsController@modify_odds')->name('manager.modify_odds');

    $router->any("fenleiguanli.index","OddsController@fenleiguanli")->name("manager.fenleiguanli.index");
    $router->any("jinyon_fenlei","OddsController@jinyon_fenlei");
    $router->any("qiyon_fenlei","OddsController@qiyon_fenlei");


    $router->any('limit.index', 'OddsController@limit')->name('manager.limit.index');
    /*问题反馈*/
    $router->any('question_back.index', 'QuestionController@question_back')->name('manager.question_back.index');
    /*消息记录*/
    $router->any('xiaoxi', 'QuestionController@xiaoxi')->name('manager.xiaoxi.index');
    /*系统消息*/
    $router->any('xitongxiaoxi', 'QuestionController@xitongxiaoxi')->name("manager.xitongxiaoxi.index");
    $router->any('look_xiaoxi', 'QuestionController@look_xiaoxi');
    $router->any('look_xitongxiaoxi', 'QuestionController@look_xitongxiaoxi');
    $router->any('send_xiaoxi', 'QuestionController@send_xiaoxi');
    $router->any('send_xiaoxi_ajax', 'QuestionController@send_xiaoxi_ajax');



    $router->any('look_wenti', 'QuestionController@look_wenti');
    $router->any('replay_qurestion', 'QuestionController@replay_qurestion')->name('manager.replay_qurestiony');
    $router->any('delete_question', 'QuestionController@delete_question')->name('manager.delete_question');
    $router->any('delete_question_duo', 'QuestionController@delete_question_duo');
    $router->any('question_tishi', 'QuestionController@question_tishi')->name('manager.question_tishi');

    $router->any('del_limit_ajax', 'OddsController@del_limit_ajax')->name('manager.del_limit_ajax');
    $router->any('modify_limit', 'OddsController@modify_limit')->name('manager.modify_limit');
    $router->any('mondify_limit_ajax', 'OddsController@mondify_limit_ajax')->name('manager.mondify_limit_ajax');
    $router->any('add_play', 'OddsController@add_play')->name('manager.add_play');
    $router->any('add_limit_ajax', 'OddsController@add_limit_ajax')->name('manager.add_limit_ajax');
    $router->any('option_limit', 'OddsController@option_limit')->name('manager.option_limit');
    $router->any('option_limit_ajax', 'OddsController@option_limit_ajax')->name('manager.option_limit_ajax');

    $router->any('announcement.index', 'AnnouncementController@index')->name('manager.announcement.index');
    $router->any('announcement.add', 'AnnouncementController@add')->name('manager.announcement.add');
    $router->any('announcement.edit', 'AnnouncementController@edit')->name('manager.announcement.edit');
    $router->any('announcement.destroy', 'AnnouncementController@destroy')->name('manager.announcement.destroy');

    $router->any('excess_warning', 'userManagerController@excessWarning')->name('manager.excess_warning');
    $router->any('getUser.index', 'userManagerController@getUser')->name('manager.getUser.index');
    $router->any('fictitious.index', 'userManagerController@fictitious')->name('manager.fictitious.index');//添加虚拟账户页面
    $router->any('add_fictitious_ajax', 'agentManagerController@add_fictitious_ajax');//添加虚拟账户
    $router->any('tongji', 'userManagerController@tongji');
    $router->any('bianji', 'userManagerController@bianji');
    $router->any('balance', 'GameController@balance')->name('manager.game.balance');

    /*修改用户真实姓名*/
    $router->any('change_name', 'userManagerController@change_name');
    /*修改用户邮箱*/
    $router->any('change_email', 'userManagerController@change_email');
    /*修改用户手机号*/
    $router->any('change_mobile_number', 'userManagerController@change_mobile_number');
    /*修改用户高频彩返点*/
    $router->any('fandain_gao', 'userManagerController@fandain_gao');
    /*修改用户六合彩返点*/
    $router->any('fandain_di', 'userManagerController@fandain_di');
    /*修改用户密码*/
    $router->any('change_pwd', 'userManagerController@change_pwd');
    /*修改用户资金密码*/
    $router->any('zijin_pwd', 'userManagerController@zijin_pwd');
    /*修改用户银行卡信息*/
    $router->any('mod_bankinfo', 'userManagerController@mod_bankinfo');
    /*升级代理*/
    $router->any('shengji', 'userManagerController@shengji');
    /*修改用户密保*/
    $router->any('change_mibao', 'userManagerController@change_mibao');
    /*修改用户提现倍数*/
    $router->any('withdrawal_update', 'userManagerController@withdrawal_update');
    /*修改等级数*/
    $router->any('level_update', 'userManagerController@level_update');

    $router->any('offline_edit', 'userManagerController@offline_edit')->name('manager.offline.edit'); //修改金额
    $router->any("offline.index",'userManagerController@offline')->name("manager.offline.index");//充值申请
    $router->any("offline_chuli",'userManagerController@offline_chuli')->name("manager.offline_chuli.index");//充值处理
//    $router->any("chong_update",'userManagerController@chong_update')->name("manager.chong_update.index");//修改充值
    $router->any('user_tixiaxian', 'userManagerController@user_tixiaxian');
/*    $router->any('getOrder.index', 'userManagerController@getOrder')->name('manager.getOrder.index');
    $router->any('getOrder_back', 'userManagerController@getOrder_back')->name('manager.getOrder_back');*/
    $router->any('getOrder_detail_back', 'userManagerController@getOrder_detail_back')->name('manager.getOrder_detail_back');
    $router->any('getOrder_detail', 'userManagerController@getOrder_detail')->name('manager.getOrder_detail');
    $router->post('backout_order', 'userManagerController@backout_order');
    $router->any("modification_order","userManagerController@modification_order");
    $router->post("do_modification_order","userManagerController@do_modification_order");
    $router->any('zhuihao', 'userManagerController@zhuihao')->name("manager.zhuihao.index");

    /*投注管理*/
    $router->any('getOrder.index', 'userManagerController@getOrder')->name('manager.getOrder.index');
    $router->any('getOrder_back', 'userManagerController@getOrder_back')->name('manager.getOrder_back.index'); /*投注页面*/
    $router->any('getOrder_chedan', 'userManagerController@getOrder_chedan')->name('manager.getOrder_chedan.index');
    $router->any('look_neiron', 'userManagerController@look_neiron');
    $router->any('change_order_betvalue', 'userManagerController@change_order_betvalue');



    /*账变*/
    $router->any('zhangbian', 'JournalaccountController@index')->name("manager.zhangbian.index");
    $router->any('agent_zhangbian', 'AccountagentController@account_agent')->name("manager.account_agent.index");

    /*链接管理*/
    $router->any('relation_index', 'RelationController@index')->name("manager.relation.index");
    $router->any('delete_relation', 'RelationController@delete_relation');
    $router->any('change_relation_status', 'RelationController@change_relation_status');


    $router->any('getUser_detail', 'userManagerController@getUser_detail')->name('manager.getUser_detail');/*修改用户资料页面*/
    $router->any('becomeAgent', 'userManagerController@becomeAgent')->name('manager.becomeAgent');
    $router->any('becomeAgent_ajax', 'userManagerController@becomeAgent_ajax')->name('manager.becomeAgent_ajax');
    $router->any('resetpwd', 'userManagerController@resetpwd')->name('manager.resetpwd');
    $router->any('resetyouxiang', 'userManagerController@resetyouxiang')->name('manager.resetyouxiang');
    $router->any('resetzijinpwd', 'userManagerController@resetzijinpwd')->name('manager.resetzijinpwd');


    $router->any('agentCenter.index', 'agentManagerController@agentCenter')->name('manager.agentCenter.index');
    $router->any('agentCenter2.index', 'agentManagerController@agentCenter2')->name('manager.agentCenter2.index');
    $router->any('agent_state', 'agentManagerController@agent_state');
    $router->any('add_agent', 'agentManagerController@add_agents'); /*新增账号*/
    $router->any('add_agent_form', 'agentManagerController@add_agent_form');
    $router->any('add_agent_ajax', 'agentManagerController@add_agent_ajax');
    $router->any('change_agent_ajax', 'agentManagerController@change_agent_ajax');
    $router->any('change_agent', 'agentManagerController@change_agent');
    $router->any('qianding_gongzi', 'agentManagerController@qianding_gongzi')->name("manager.qianding_gongzi");
    $router->any('qianding_fenhon', 'agentManagerController@qianding_fenhon');
    $router->any('ajax_qianding_gongzi', 'agentManagerController@ajax_qianding_gongzi');
    $router->any('ajax_qianding_fenhon', 'agentManagerController@ajax_qianding_fenhon');
    $router->any('change_fenhon', 'agentManagerController@change_fenhon');
    $router->any('look_fenhon', 'agentManagerController@look_fenhon');
    $router->any('ajax_change_fenhon', 'agentManagerController@ajax_change_fenhon');
    $router->any('change_gongzi', 'agentManagerController@change_gongzi');
    $router->any('look_gongzi', 'agentManagerController@look_gongzi');
    $router->any('ajax_change_gongzi', 'agentManagerController@ajax_change_gongzi');



    $router->any('second_agent', 'agentManagerController@second_agent')->name('manager.second_agent');
    $router->any('editAgent', 'agentManagerController@editAgent')->name('manager.editAgent');
    $router->any('agent_loginState', 'agentManagerController@agent_loginState')->name('manager.agent_loginState');
    $router->any('agent_children', 'agentManagerController@agent_children')->name('manager.agent_children');
    $router->any('profit_detail', 'agentManagerController@profit_detail')->name('manager.profit_detail');
    $router->any('agent_profit.index', 'agentManagerController@agent_profit')->name('manager.agent_profit.index');
    $router->any('agent_profit.history', 'agentManagerController@agent_profit_history')->name('manager.agent_profit.history');
    $router->any('getAgent_detail', 'agentManagerController@getAgent_detail')->name('manager.getAgent_detail');
    $router->any('modifyAgent_ajax', 'agentManagerController@modifyAgent_ajax')->name('manager.editAgent');
    $router->any('user_state_ajax', 'userManagerController@user_state_ajax')->name('manager.user_state_ajax');
    $router->any('system.index', 'SystemController@index')->name('manager.system.index');
    $router->any('paymentmethod.index', 'SystemController@paymentmethod')->name('manager.paymentmethod.index');
    $router->any('change_payment_ajax', 'SystemController@change_payment_ajax')->name('manager.change_payment_ajax');
    $router->any('add_payment', 'SystemController@add_payment')->name('manager.add_payment');
    $router->any("shoukuan",'GatheringController@shoukuan')->name("");
    $router->any("set_img","GatheringController@set_img")->name("set_img");
    $router->any("recharge_lis","userManagerController@recharge_lis")->name("manager.recharge_lis.index");  //线下充值通道
    $router->any("recharge_edit","userManagerController@recharge_edit")->name("manager.recharge_edit.index");  //修改通道
    $router->any("recharge_add","userManagerController@recharge_add")->name("manager.recharge_add.index");  //添加通道
    $router->any("recharge_icon","userManagerController@recharge_icon")->name("manager.recharge_icon.index");  //修改通道图片
    $router->any("shoukuan",'GatheringController@shoukuan')->name("manager.shoukuan.index");

    /*开启或关闭*/
    $router->any('onoff_ajax', 'SystemController@onoff_ajax');
    $router->any("banner.index",'SystemController@banner')->name("manager.banner.index");
    $router->any('banner.add', 'SystemController@banner_add')->name('manager.banner.add');
    $router->any('banner.edit', 'SystemController@banner_edit')->name('manager.banner.edit');
    $router->any('banner.destroy', 'SystemController@banner_destroy')->name('manager.banner.destroy');
    $router->any('pro.add', 'SystemController@pro_add')->name('manager.pro.add');
    $router->any('pro.edit', 'SystemController@pro_edit')->name('manager.pro.edit');
    $router->any("pro.index",'SystemController@pro')->name("manager.pro.index");
    $router->any('pro.destroy', 'SystemController@pro_destroy')->name('manager.pro.destroy');




    $router->any("change_shoukuan",'GatheringController@change_shoukuan')->name("change_shoukuan");

    $router->any("save.index","SaveController@index")->name("manager.save.index");
    $router->any("delete_ip","SaveController@delete_ip");
    $router->any("huifu_ip","SaveController@huifu_ip");
    $router->any("save_select_index","SaveController@index");
    $router->any("add_ip","SaveController@add_ip");
    $router->any("add_ip_ajax","SaveController@add_ip_ajax");


    $router->any('bank.index', 'SystemController@bank')->name('manager.bank.index');
    $router->any('del_bank_ajax', 'SystemController@del_bank_ajax')->name('manager.del_bank_ajax');
    $router->any('System.add_bank', 'SystemController@add_bank')->name('manager.add_bank');
    $router->any('question.index', 'SystemController@question')->name('manager.question.index');
    $router->any('add_question', 'SystemController@add_question')->name('manager.add_question');


    $router->any('modify_user_ajax', 'userManagerController@modify_user_ajax')->name('manager.modify_user_ajax');
    $router->any('user_withdraw_verify.index', 'userManagerController@user_withdraw_verify')->name('manager.user_withdraw_verify.index');
    $router->any('user_withdraw_paying', 'userManagerController@user_withdraw_paying')->name('manager.user_withdraw_paying');
    $router->any('user_withdraw_payed', 'userManagerController@user_withdraw_payed')->name('manager.user_withdraw_payed');
    $router->any('user_withdraw_back', 'userManagerController@user_withdraw_back')->name('manager.user_withdraw_back');
    $router->any('user_verify_excel', 'userManagerController@user_verify_excel')->name('manager.user_verify_excel');
    $router->any('user_get_excel', 'userManagerController@user_get_excel')->name('manager.user_get_excel');
    $router->any('user_submit_excel', 'userManagerController@user_submit_excel')->name('manager.user_submit_excel');
    $router->any('user_change_status_ajax', 'userManagerController@user_change_status_ajax')->name('manager.user_change_status_ajax');
    $router->any('user_change_back_ajax', 'userManagerController@user_change_back_ajax')->name('manager.user_change_back_ajax');
    $router->any('recharge', 'userManagerController@recharge')->name('manager.recharge');
    //$router->any('recharge', 'userManagerController@recharge')->name('manager.recharge');
    $router->any('lowerScore', 'userManagerController@lowerScore')->name('manager.lowerScore');

    $router->any("user_chongzhi",'userManagerController@user_chongzhi')->name("manager.chongzhi.index");
    $router->any("tixian_tishi",'userManagerController@tixian_tishi')->name("manager.tixian_tishi");
    $router->any("chongzhi_tishi",'userManagerController@chongzhi_tishi')->name("manager.chongzhi_tishi");
    $router->any("chuli",'userManagerController@chuli');
    $router->any("chuli_ajax",'userManagerController@chuli_ajax');
    $router->any("chongzhichuli",'userManagerController@chongzhichuli');
    $router->any("chongzhichuli_ajax",'userManagerController@chongzhichuli_ajax');
    $router->any("user_chongzhi.deletOrder",'userManagerController@deletOrder');


    $router->any("user_qiyue",'userManagerController@user_qiyue')->name("manager.qiyue.index");
    $router->any("user_qiyue_fenhon",'userManagerController@user_qiyue_fenhon')->name("manager.fenhon.index");
    $router->any("user_gongzi_detail",'userManagerController@user_gongzi_detail')->name("manager.gongzi.detail");
    $router->any("user_fenhon_detail",'userManagerController@user_fenhon_detail')->name("manager.fenhon.detail");

    $router->any('order_ratio.index', 'statManagerController@order_ratio')->name('manager.order_ratio.index');

    $router->any("huodon",'ActiveController@huodon')->name("manager.huodon.index");
    $router->any("active_user",'ActiveController@active_user')->name("manager.active_user");

    /*银行卡管理*/
    $router->any("bank",'BankController@bank')->name("manager.userbank.index"); /*银行卡管理页面*/
    $router->any("change_bank",'BankController@change_bank');
    $router->any("change_bank_ajax",'BankController@change_bank_ajax');
    $router->any("jiedon_bank_ajax",'BankController@jiedon_bank_ajax');
    $router->any("donjie_bank_ajax",'BankController@donjie_bank_ajax');
    $router->any("jiebang_bank_ajax",'BankController@jiebang_bank_ajax');/*解绑银行卡*/
    $router->any("add_bank",'BankController@add_bank');
    $router->any("add_bank_ajax",'BankController@add_bank_ajax');
    /*查看银行卡信息*/
    $router->any("look_bank",'BankController@look_bank');
    /*修改银行卡信息*/
    $router->any("midification_account",'BankController@midification_account');
    /*修改银行卡类型*/
    $router->any("midification_leixing",'BankController@midification_leixing');




    /*日志信息*/
    $router->any("log",'LoginlogController@log')->name("manager.log.index");    /*登录日志*/
    $router->any("tixiaxian",'LoginlogController@tixiaxian');

    $router->any('withdraw_verify.index', 'agentManagerController@withdraw_verify')->name('manager.withdraw_verify.index');
    $router->any('withdraw_paying', 'agentManagerController@withdraw_paying')->name('manager.withdraw_paying');
    $router->any('withdraw_payed', 'agentManagerController@withdraw_payed')->name('manager.withdraw_payed');
    $router->any('withdraw_back', 'agentManagerController@withdraw_back')->name('manager.withdraw_back');
    $router->any('changeStatus_ajax', 'agentManagerController@changeStatus_ajax')->name('manager.changeStatus_ajax');
    $router->any('payChange_status_ajax', 'agentManagerController@payChange_status_ajax')->name('manager.payChange_status_ajax');
    $router->any('backStatus_ajax', 'agentManagerController@backStatus_ajax')->name('manager.backStatus_ajax');
    $router->any('verify_excel', 'agentManagerController@verify_excel')->name('manager.verify_excel');
    $router->any('get_excel', 'agentManagerController@get_excel')->name('manager.get_excel');
    $router->any('submit_excel', 'agentManagerController@submit_excel')->name('manager.submit_excel');

    $router->any('stat.recharge.index', 'statManagerController@statRecharge')->name('manager.stat.recharge.index');
    $router->any('stat.recharge_detail', 'statManagerController@statRecharge_detail')->name('manager.stat.recharge_detail');
    $router->any('day_recharge_user', 'statManagerController@day_recharge_user')->name('manager.day_recharge_user');

    $router->any('stat.user.index', 'statManagerController@statUser')->name('manager.stat.user.index'); /*用户盈亏数据*/
    $router->any('stat.plat.index', 'statManagerController@statPlat')->name('manager.stat.plat.index'); /*用户盈亏数据*/
    $router->any('stat.user_detail', 'statManagerController@statUser_detail')->name('manager.stat.user_detail');

    $router->any('stat.income.index', 'statManagerController@statIncome')->name('manager.stat.income.index');
    $router->any('day_money_detail', 'statManagerController@day_money_detail')->name('manager.day_money_detail');
    $router->any('server_charge_detail', 'statManagerController@server_charge_detail')->name('manager.server_charge_detail');
    $router->any('share_profit_detail', 'statManagerController@share_profit_detail')->name('manager.share_profit_detail');
    $router->any('day_income_user', 'statManagerController@day_income_user')->name('manager.day_income_user');
    $router->any('server_charge_user', 'statManagerController@server_charge_user')->name('manager.server_charge_user');
    $router->any('day_income_stat', 'statManagerController@day_income_stat')->name('manager.day_income_stat');
    $router->any('share_profit_user', 'statManagerController@share_profit_user')->name('manager.share_profit_user');


    $router->any('manager_modAgent', 'userManagerController@modAgent')->name('manager.modAgent');
    $router->any('manager.modAgent_one', 'userManagerController@modAgent_one')->name('manager.modAgent_one');
    $router->any('manager.modDailyPwd', 'userManagerController@modDailyPwd')->name('manager.modDailyPwd');



    $router->any('user_result_detail', 'userManagerController@user_result_detail')->name('user_result_detail');
    $router->any('user_result_history_detail', 'userManagerController@user_result_history_detail')->name('manager.user_result_history_detail');
    $router->any('user_withdraw_detail', 'userManagerController@user_withdraw_detail')->name('user_withdraw_detail');
    $router->any('user_recharge_detail', 'userManagerController@user_recharge_detail')->name('user_recharge_detail');

    $router->any('stat.agent.index', 'statManagerController@statAgent')->name('manager.stat.agent.index');
    $router->any('stat.agent_detail', 'statManagerController@statAgent_detail')->name('manager.stat.agent_detail');
    $router->any('day_agent_user', 'statManagerController@day_agent_user')->name('manager.day_agent_user');

    $router->any('accounts.index', 'AccountsController@index')->name('manager.accounts.index');
    $router->any('accounts.add', 'AccountsController@addAccount')->name('manager.accounts.add');
    $router->any('accounts.edit', 'AccountsController@editAccount')->name('manager.accounts.edit');
    $router->any('accounts.destroy', 'AccountsController@destroyAccount')->name('manager.accounts.destroy');

    $router->any('accounts.group.index', 'AccountsController@groups')->name('manager.accounts.group.index');
    $router->any('accounts.addGroup', 'AccountsController@addGroup')->name('manager.accounts.addGroup');
    $router->any('accounts.editGroup', 'AccountsController@editGroup')->name('manager.accounts.editGroup');
    $router->any('accounts.modifyLimit', 'AccountsController@modifyLimit')->name('manager.accounts.modifyLimit');
    $router->any('warning_tone_ajax', 'userManagerController@warning_tone_ajax');
    $router->any('question_warning_tone', 'userManagerController@question_warning_tone')->name('manager.question_warning_tone');

    //提现审核
    $router->any('user_shenghe', 'userManagerController@user_shenghe_withdraw_verify')->name('withdraw.user_shenghe_withdraw_verify');
    $router->any('user_betOrder_shenghe', 'userManagerController@user_betOrder_shenghe')->name('withdraw.user_betOrder_shenghe');
    $router->any('user_moneyList_shenghe', 'userManagerController@user_moneyList_shenghe')->name('withdraw.user_moneyList_shenghe');

    $router->any('app_system', 'SystemController@app_system')->name('manager.app_system.index');

    $router->any('app_fenhong', 'SystemController@app_fenhong')->name('manager.fenhong.index');
    $router->any('app_fenxiang', 'SystemController@app_fenxiang')->name('manager.fenxiang.index');
    $router->any('horn.index', 'SystemController@horn')->name('manager.horn.index');
    $router->any('horn_page.index', 'SystemController@horn_page')->name('manager.horn_page.index');
    $router->any('horn_ajax.index', 'SystemController@horn_ajax')->name('manager.horn_ajax.index');

    $router->any('house.house', 'HouseController@house')->name('manager.house.index');
    $router->any('house.edit', 'HouseController@edit')->name('manager.house.edit');
    $router->any('house.xe_set', 'HouseController@xe_set')->name('manager.house.xe_set');
    $router->any('app_red', 'SystemController@red')->name('manager.red.index');
    $router->any('manager.red.result', 'SystemController@red_result')->name('manager.red.result');

    $router->any('manager.banner_mod_open', 'SystemController@banner_mod_open')->name('manager.banner.mod_open');
    $router->any('manager.banner_mod_close','SystemController@banner_mod_close')->name('manager.banner.mod_close');
    $router->any('manager.limit_mod_open1', 'SystemController@limit_mod_open1')->name('limit.mod_open1');
    $router->any('manager.limit_mod_close1', 'SystemController@limit_mod_close1')->name('limit.mod_close1');
    $router->any('manager.limit_mod_open2', 'SystemController@limit_mod_open2')->name('limit.mod_open2');
    $router->any('manager.limit_mod_close2', 'SystemController@limit_mod_close2')->name('limit.mod_close2');
    $router->any('limit.mod_value', 'SystemController@limit_mod_value')->name('limit.mod_value');
    /*-------------daili------------*/
    $router->any('teamStat_hou', 'houuserManagerController@teamStat_hou');


});

Route::group(['prefix' => 'agent','namespace' => 'Agent'],function ($router)
{   $router->get('main', 'DashboardController@main');
    $router->get('/', 'DashboardController@index');
    $router->get('dash', 'DashboardController@index');
    $router->get('login', 'LoginController@showLoginForm')->name('manager.login');
    $router->post('login', 'LoginController@login');
    $router->any('secure', 'LoginController@secure');
    $router->any('logout', 'LoginController@logout');
    $router->get('captcha/{tmp}', 'CaptchaController@captcha');

});
Route::group(['prefix' => 'agent', 'middleware' => 'auth.agent:agent', 'namespace' => 'Agent'], function ($router)
{
    $router->any('getUser', 'userManagerController@getUser');

    $router->any('getUser_detail', 'userManagerController@getUser_detail');
    $router->any('becomeAgent', 'userManagerController@becomeAgent');
    $router->any('becomeAgent_ajax', 'userManagerController@becomeAgent_ajax');
   // $router->any('getUser_detail', 'userManagerController@getUser_detail');
    $router->any('withdrawCenter', 'userManagerController@withdrawCenter');
    $router->any('applyWithdraw', 'userManagerController@applyWithdraw');
    $router->any('applyWithdraw_ajax', 'userManagerController@applyWithdraw_ajax');

    $router->any('agent_loginState', 'agentManagerController@agent_loginState');
    $router->any('withdraw_verify', 'agentManagerController@withdraw_verify');
    $router->any('withdraw_paying', 'agentManagerController@withdraw_paying');
    $router->any('withdraw_payed', 'agentManagerController@withdraw_payed');
    $router->any('withdraw_back', 'agentManagerController@withdraw_back');
    $router->any('changeStatus_ajax', 'agentManagerController@changeStatus_ajax');
    $router->any('payChange_status_ajax', 'agentManagerController@payChange_status_ajax');
    $router->any('backStatus_ajax', 'agentManagerController@backStatus_ajax');
    $router->any('verify_excel', 'agentManagerController@verify_excel');
    $router->any('get_excel', 'agentManagerController@get_excel');
    $router->any('submit_excel', 'agentManagerController@submit_excel');

    $router->any('payment_excel', 'agentManagerController@payment_excel');
    $router->any('getAgent_detail', 'agentManagerController@getAgent_detail');
    $router->any('agentCenter', 'agentManagerController@agentCenter');
    $router->any('editAgent', 'agentManagerController@editAgent');
    $router->any('modifyAgent_ajax', 'agentManagerController@modifyAgent_ajax');
    $router->any('agent_children', 'agentManagerController@agent_children');
    $router->any('profit_detail', 'agentManagerController@profit_detail');
    $router->any('agent_profit', 'agentManagerController@agent_profit');
    $router->any('agent_income', 'agentManagerController@agent_income');

    $router->any('user_result_detail', 'userManagerController@user_result_detail')->name('user_result_detail');
    $router->any('user_result_history_detail', 'userManagerController@user_result_history_detail')->name('agent.user_result_history_detail');
    $router->any('user_withdraw_detail', 'userManagerController@user_withdraw_detail')->name('user_withdraw_detail');
    $router->any('user_recharge_detail', 'userManagerController@user_recharge_detail')->name('user_recharge_detail');

    $router->any('getOrder', 'userManagerController@getOrder');
//    $router->any('getOrder_back', 'userManagerController@getOrder_back');
    $router->any('getOrder_detail_back', 'userManagerController@getOrder_detail_back');
    $router->any('getOrder_detail', 'userManagerController@getOrder_detail');


    $router->any('stat.user.index', 'statManagerController@statUser')->name('agent.stat.user.index');  /*用户盈亏数据*/
   // $router->any('stat.user.index', 'statManagerController@statUser')->name('agent.stat.user.index');  /*用户盈亏数据*/

    $router->any('stat.user_detail', 'statManagerController@statUser_detail')->name('agent.stat.user_detail');
    $router->any('day_income_stat', 'statManagerController@day_income_stat')->name('agent.day_income_stat');

    $router->any('teamMsg', 'userManagerController@teamMsg');
    $router->any('teamJou', 'userManagerController@teamJou');
    $router->any('teamStat', 'userManagerController@teamStat');
    //代理
//    $router->any('agentCenter2.index', 'agentManagerController@agentCenter2')->name('manager.agentCenter2.index');
    $router->any('teamStat_hou', 'houuserManagerController@teamStat_hou');


});