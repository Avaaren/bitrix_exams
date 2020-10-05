<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оценка производительности");
?><table style="border:1px solid; width: 100%;">
<tbody>
<tr>
	<td>
 <a href="http://bitrix.site/bitrix/admin/perfmon_hit_list.php?lang=ru&set_filter=Y&find_script_name=%2Fs2%2Fproducts%2Findex.php">/s2/products/index.php</a>
	</td>
	<td>
		 2
	</td>
	<td>
		 33.29%
	</td>
	<td>
		 17
	</td>
	<td>
		 1.6417
	</td>
</tr>
<tr>
	<td>
 <a href="http://bitrix.site/bitrix/admin/perfmon_hit_list.php?lang=ru&set_filter=Y&find_script_name=%2Fbitrix%2Furlrewrite.php">/bitrix/urlrewrite.php</a>
	</td>
	<td>
		 2
	</td>
	<td>
		 12.94%
	</td>
	<td>
		 9
	</td>
	<td>
		 1.2053
	</td>
</tr>
<tr>
	<td>
 <a href="http://bitrix.site/bitrix/admin/perfmon_hit_list.php?lang=ru&set_filter=Y&find_script_name=%2Fs2%2Fservices%2Findex.php">/s2/services/index.php</a>
	</td>
	<td>
		 2
	</td>
	<td>
		 10.24%
	</td>
	<td>
		 5
	</td>
	<td>
		 1.7171
	</td>
</tr>
<tr>
	<td>
 <a href="http://bitrix.site/bitrix/admin/perfmon_hit_list.php?lang=ru&set_filter=Y&find_script_name=%2Fs2%2Fex2%2Fsimplecomp%2Findex.php">/s2/ex2/simplecomp/index.php</a>
	</td>
	<td>
		 2
	</td>
	<td>
		 10.11%
	</td>
	<td>
		 5
	</td>
	<td>
		 1.6950
	</td>
</tr>
<tr>
	<td>
 <a href="http://bitrix.site/bitrix/admin/perfmon_hit_list.php?lang=ru&set_filter=Y&find_script_name=%2Fs2%2Fcontacts%2Findex.php">/s2/contacts/index.php</a>
	</td>
	<td>
		 2
	</td>
	<td>
		 7.78%
	</td>
	<td>
		 4
	</td>
	<td>
		 1.6302
	</td>
</tr>
</tbody>
</table>
 <br>
 По умолчанию&nbsp;<br>
<table>
<tbody>
<tr>
	<td>
		До
	</td>
	<td>
		 &nbsp;
	</td>
	<td>
		 main
	</td>
	<td>
		 17311
	</td>
	<td>
 <br>
	</td>
	<td>
 <br>
	</td>
	<td>
 <a href="http://bitrix.site/bitrix/admin/perfmon_cache_list.php?lang=ru&group=none&set_filter=Y&find_base_dir=%2Fbitrix%2Fcache%2F&find_init_dir=%2Fs2%2Fex2%2Fsimplecomp%2F">/s2/ex2/simplecomp/</a>
	</td>
	<td>
 <a target="blank" href="http://bitrix.site/bitrix/admin/fileman_file_view.php?path=bitrix%2Fcache%2F%2Fs2%2Fex2%2Fsimplecomp%2F%2Fc5%2Fc5ab8d3995add655cc8f3ad1ee90ee57.php&lang=ru">/c5/c5ab8d3995add655cc8f3ad1ee90ee57.php</a>
	</td>
</tr>
</tbody>
</table>
 (В отладке)<br>
 custom:simplecomp.exam:&nbsp;<nobr>0.0007 с</nobr><nobr>;&nbsp;кеш:&nbsp;17 КБ<br>
 -----------------------------------------------------------------------------------------<br>
 </nobr>После включения только необходимых параметров<br>
Убрал html код из кеша и ненужный запрос.<br>
<table>
<tbody>
<tr>
	<td>
		После
	</td>
	<td>
		 &nbsp;
	</td>
	<td>
		 main
	</td>
	<td>
		 13100
	</td>
	<td>
 <br>
	</td>
	<td>
		 &nbsp;&nbsp;
	</td>
	<td>
 <a href="http://bitrix.site/bitrix/admin/perfmon_cache_list.php?lang=ru&group=none&set_filter=Y&find_base_dir=%2Fbitrix%2Fcache%2F&find_init_dir=%2Fs2%2Fcustom%2Fsimplecomp.exam%2F1a7">/s2/custom/simplecomp.exam/1a7</a>
	</td>
	<td>
 <a target="blank" href="http://bitrix.site/bitrix/admin/fileman_file_view.php?path=bitrix%2Fcache%2F%2Fs2%2Fcustom%2Fsimplecomp.exam%2F1a7%2F47%2F472c1ff5886158fc2255eeb8b15ba4eb.php&lang=ru">/47/472c1ff5886158fc2255eeb8b15ba4eb.php</a><br>
	</td>
</tr>
</tbody>
</table>
 (В отладке)<br>
 custom:simplecomp.exam:&nbsp;<nobr>0.0056 с</nobr>;&nbsp;Запросов: 4 (0.0023 с)<nobr>;&nbsp;кеш:&nbsp;13 КБ</nobr><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>