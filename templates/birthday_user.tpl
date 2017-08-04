<{if isset($birthday_user)}>
    <{$breadcrumb}>
    <br>
    <br>
    <div style="margin-left: 10px; text-align: justify;">
        <{if trim($birthday_user.birthday_full_imgurl) != ''}>
            <div style="margin: 5px 10px;float: left;">
                <img src="<{$birthday_user.birthday_full_imgurl}>" alt="<{$birthday_user.birthday_href_title}>">
            </div>
        <{elseif trim($birthday_user.birthday_user_user_avatar) != ''}>
            <div style="margin: 5px 10px;float: left;">
                <img src="<{$xoops_url}>/uploads/<{$birthday_user.birthday_user_user_avatar}>"
                     alt="<{$birthday_user.birthday_href_title}>">
            </div>
        <{/if}>
        <h3><{$birthday_user.birthday_fullname}></h3>
        <{$smarty.const._BIRTHDAY_DATE}> : <{$birthday_user.birthday_formated_date}>
        <br>
        <{$smarty.const._BIRTHDAY_FIRSTNAME}> : <{$birthday_user.birthday_firstname}>
        <br>
        <{$smarty.const._BIRTHDAY_LASTNAME}> : <{$birthday_user.birthday_lastname}>
        <br><br>
        <{$birthday_user.birthday_description}>
    </div>
    <{if $birthday_user.birthday_uid > 0}>
        <br>
        <br>
        <div align="center"><b><a
                        href="<{$xoops_url}>/userinfo.php?uid=<{$birthday_user.birthday_uid}>"><{$smarty.const._AM_BIRTHDAY_XOOPS_PROFILE}></a></b>
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
