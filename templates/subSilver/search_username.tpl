
<script language="javascript" type="text/javascript">
<!--
function refresh_username(selected_username)
{
	if (!window.opener || window.opener.closed)
	{
		return;
	}

	var opener_document = window.opener.document;
	var username_field = null;
	var post_form = opener_document.forms['post'];
	if (post_form && post_form.elements['username'] && post_form.elements['username'].type == 'text')
	{
		username_field = post_form.elements['username'];
	}
	else
	{
		for (var i = 0; i < opener_document.forms.length && !username_field; i++)
		{
			var candidate = opener_document.forms[i].elements['username'];
			if (candidate && candidate.type == 'text' && !candidate.disabled)
			{
				username_field = candidate;
			}
		}
	}

	if (username_field)
	{
		username_field.value = selected_username;
		username_field.focus();
		window.opener.focus();
		window.close();
	}
}
//-->
</script>

<form method="post" name="search" action="{S_SEARCH_ACTION}">
<table width="100%" border="0" cellspacing="0" cellpadding="10">
	<tr>
		<td><table width="100%" class="forumline" cellpadding="4" cellspacing="1" border="0">
			<tr> 
				<th class="thHead" height="25">{L_SEARCH_USERNAME}</th>
			</tr>
			<tr> 
				<td valign="top" class="row1"><span class="genmed"><br><input type="text" name="search_username" value="{USERNAME}" class="post" onFocus="Active(this)" onBlur="NotActive(this)">&nbsp; <input type="submit" name="search" value="{L_SEARCH}" class="liteoption"></span><br><span class="gensmall">{L_SEARCH_EXPLAIN}</span><br>
				<!-- BEGIN switch_select_name -->
				<span class="genmed">{L_UPDATE_USERNAME}<br><select name="username_list">{S_USERNAME_OPTIONS}</select>&nbsp; <input type="submit" class="liteoption" onClick="refresh_username(this.form.username_list.options[this.form.username_list.selectedIndex].value);return false;" name="use" value="{L_SELECT}"></span><br>
				<!-- END switch_select_name -->
				<br><span class="genmed"><a href="javascript:window.close();" class="genmed">{L_CLOSE_WINDOW}</a></span></td>
			</tr>
		</table></td>
	</tr>
</table>
</form>
