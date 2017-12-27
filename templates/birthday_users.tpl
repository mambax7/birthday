<style type="text/css">
    .heyula {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .heyula li {
        width: 10%;
        float: left;
    }
</style>

<{if count($birthday_users) > 0}>
    <ol class="breadcrumb">
        <li><a href="index.php"><{$breadcrumb}></a></li>
    </ol>
    <p align="center">
        <a href="<{$xoops_url}>/modules/birthday/index.php"><img src="<{$xoops_url}>/modules/birthday/images/logo.png" alt="" class="img-thumbnail"/></a>
    </p>
    <br>
    <ul class="heyula">
        <{foreach item=birthday_user from=$birthday_users}>
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">

                    <{if trim($birthday_user.birthday_full_imgurl) != ''}>
                        <img src="<{$birthday_user.birthday_full_imgurl}>" alt="<{$birthday_user.birthday_href_title}>" class="img-thumbnail">
                    <{elseif trim($birthday_user.birthday_user_user_avatar) != ''}>
                        <img src="<{$xoops_url}>/uploads/<{$birthday_user.birthday_user_user_avatar}>" alt="<{$birthday_user.birthday_href_title}>"
                             class="img-thumbnail"/>
                    <{else}>
                        <img src="<{$xoops_url}>/modules/birthday/images/nophoto.jpg" alt="<{$birthday_user.birthday_href_title}>" width="130"
                             class="img-thumbnail"/>
                    <{/if}>

                    <div class="caption">
                        <div style="text-align: center;"><h3><a href="<{$smarty.const.BIRTHDAY_URL}>user.php?birthday_id=<{$birthday_user.birthday_id}>"
                                                                title="<{$birthday_user.birthday_href_title}>"><{$birthday_user.birthday_fullname}></a></h3></div>
                        <div style="text-align: center;"><p><span class="glyphicon glyphicon-calendar"></span>&nbsp;<span class="label label-danger"><{$birthday_user.birthday_formated_date}></span>&nbsp;<span
                                        class="label label-success"><a
                                            href="<{$smarty.const.BIRTHDAY_URL}>user.php?birthday_id=<{$birthday_user.birthday_id}>"
                                            title="<{$birthday_user.birthday_href_title}>">more</a></span></p></div>
                    </div>
                </div>
                </br>
            </div>
        <{/foreach}>
    </ul>
<{else}>
    <h3><{$smarty.const._BIRTHDAY_ERROR3}></h3>
<{/if}>

<{if isset($pagenav)}>
    <div align="center"><{$pagenav}></div>
<{/if}>




