<{if isset($birthday_user)}>
    <ol class="breadcrumb">
        <li><a href="index.php"><{$breadcrumb}></a></li>
        <{if $xoops_isadmin}>
            <li>[ <a href="<{$xoops_url}>/modules/birthday/admin/main.php?op=edit&id=<{$birthday_user.birthday_id}>"><{$smarty.const._EDIT}></a> ]</li>
        <{/if}>
    </ol>
    <div style="margin-left: 10px; text-align: justify;">

        <b><span class="glyphicon glyphicon-user"></span>&nbsp;<{$birthday_user.birthday_fullname}></b>
        <br>
        <{if trim($birthday_user.birthday_full_imgurl) != ''}>
            <div style="margin: 5px 10px;float: left;">

                <img src="<{$birthday_user.birthday_full_imgurl}>" alt="<{$birthday_user.birthday_href_title}>" class="img-thumbnail">

            </div>
        <{elseif trim($birthday_user.birthday_user_user_avatar) != ''}>
            <div style="margin: 5px 10px;float: left;">

                <img src="<{$xoops_url}>/uploads/<{$birthday_user.birthday_user_user_avatar}>" alt="<{$birthday_user.birthday_href_title}>"/>

            </div>
        <{else}>
            <div style="margin: 5px 10px;float: left;">

                <img src="<{$xoops_url}>/modules/birthday/images/nophoto.jpg" alt="<{$birthday_user.birthday_href_title}>" width="130"/>

            </div>
        <{/if}>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
         </br>

        <b><span class="glyphicon glyphicon-calendar"></span>&nbsp;<{$smarty.const._BIRTHDAY_DATE}></b> :
        <{$birthday_user.birthday_formated_date}>

        <br>

        <b><span class="glyphicon glyphicon-check"></span>&nbsp;<{$smarty.const._BIRTHDAY_FIRSTNAME}></b> : <{$birthday_user.birthday_firstname}>

        <br>

        <b><span class="glyphicon glyphicon-info-sign"></span>&nbsp;<{$smarty.const._BIRTHDAY_LASTNAME}></b> :
        <{$birthday_user.birthday_lastname}>

        <br><br>

        <div class="alert alert-success"><b>
                <{$smarty.const._BIRTHDAY_DESCRIPTION}>
                <{*<{$title1}>*}>
            </b> :</div>
        <div class="well well-lg"><{$birthday_user.birthday_description}></div>

    </div>
    <{if $birthday_user.birthday_uid > 0}>
        <br>
        <br>
        <div align="center"><b><a href="<{$xoops_url}>/userinfo.php?uid=<{$birthday_user.birthday_uid}>"><span class="label label-danger"><{$smarty.const._AM_BIRTHDAY_XOOPS_PROFILE}></span></a></b>
        </div>
        <br>
    <{/if}>

<{/if}>

<div style="text-align: center; padding: 3px; margin:3px;">

    <{$commentsnav}>

    <{$lang_notice}>

</div>


<div style="margin:3px; padding: 3px;">

    <{if $comment_mode == "flat"}>

        <{include file="db:system_comments_flat.tpl"}>

    <{elseif $comment_mode == "thread"}>

        <{include file="db:system_comments_thread.tpl"}>

    <{elseif $comment_mode == "nest"}>

        <{include file="db:system_comments_nest.tpl"}>

    <{/if}>

</div>
