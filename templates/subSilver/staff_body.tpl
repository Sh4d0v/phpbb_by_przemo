<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
  <tr> 
	<td align="left"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
  </tr>
</table>

<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
  <tr>
        <th class="thTop">{L_USERNAME}</th>
        <th class="thTop">{L_FORUMS}</th>
        <th class="thTop">{L_POSTS}</th>
        <th class="thTop">{L_JOINED}</th>
        <th class="thTop">{L_EMAIL}</th>
        <th class="thTop">PM</th>
        <th class="thCornerR">WWW</th>
  </tr>
<!-- BEGIN staff -->
  <tr> 
        <td valign="top" class="row1" nowrap="nowrap"><a href="{staff.U_NAME}" class="genmed"{staff.USERNAME_COLOR}><b>{staff.NAME}</b></a><span class="postdetails">{staff.LEVEL}{staff.RANK}{staff.RANK_IMAGE}{staff.AVATAR}</span></td>
        <td valign="top" class="row2" nowrap="nowrap"><span class="genmed">{staff.FORUMS}</span>&nbsp;</td>
        <td valign="top" align="right" class="row1" nowrap="nowrap"><span class="gensmall">{staff.POSTS} &nbsp;<br>{staff.POST_PERCENT} &nbsp;<br>{staff.POST_DAY}</span>&nbsp;</td>
        <td valign="top" class="row2" align="right" nowrap="nowrap"><span class="gensmall">{staff.JOINED}<br>[{staff.PERIOD}]</span></td>
        <td align="center" class="row1">{staff.MAIL}</td>
		<td align="center" class="row2">{staff.PM}</td>
		<td align="center" class="row2">{staff.WWW}</td>
  </tr>
<!-- END staff -->
</table>
