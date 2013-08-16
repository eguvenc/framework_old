<div id="content">

<a name="0"></a>

## Direct Query

To submit a query, use the following function:

`<var>$this</var>-&gt;db-&gt;query('YOUR&nbsp;QUERY&nbsp;HERE');`

The <dfn>query()</dfn> function returns a database result **object** when "read" type queries are run,
which you can use to [show your results](/user_guide/en/1.0.1/generating-pdo-query-results.html). When retrieving data you will typically assign the query to your own variable, like this:

`<var><var>$query</var></var>&nbsp;=&nbsp;<var>$this</var>-&gt;db-&gt;query('YOUR&nbsp;QUERY&nbsp;HERE');`

<a name="1"></a>

## PDO Exec Query

This query type same as direct query just it returns to $affected_rows automatically. You should use **exec_query** function for INSERT, UPDATE, DELETE operations. 

`$affected_rows&nbsp;=&nbsp;<var>$this</var>-&gt;db-&gt;exec_query('INSERT&nbsp;QUERY');&nbsp;

<kbd>echo</kbd>&nbsp;$affected_rows;&nbsp;&nbsp;<span class="output">&nbsp;//output&nbsp;&nbsp;1</span>`

# Escaping Queries

It's a very good security practice to escape your data before submitting it into your database.
Obullo has three methods that help you do this:

### $this-&gt;db-&gt;escape()
 This function determines the data type so that it
can escape only string data.  It also automatically adds single quotes around the data and it can automatically 
determine data types.

`$sql&nbsp;=&nbsp;"INSERT&nbsp;INTO&nbsp;table&nbsp;(title)&nbsp;VALUES(".<var>$this</var>-&gt;db-&gt;escape(**(string)**$title).")";`

Supported data types: <samp>(int), (string), (boolean)</samp>

### $this-&gt;escape_str();

This function escapes the data passed to it, regardless of type. Most of the time you'll use the above function rather than this one. Use the function like this: 

`$$sql&nbsp;=&nbsp;"INSERT&nbsp;INTO&nbsp;table&nbsp;(title)&nbsp;VALUES('".<var>$this</var>-&gt;db-&gt;escape_str($title)."')";`

### $this-&gt;db-&gt;escape_like()
  This method should be used when strings are to be used in LIKE
conditions so that LIKE wildcards ('%', '_') in the string are also properly escaped.

`$search&nbsp;=&nbsp;'20%&nbsp;raise';&lt;br&nbsp;/&gt;
$sql&nbsp;=&nbsp;"SELECT&nbsp;id&nbsp;FROM&nbsp;table&nbsp;WHERE&nbsp;column&nbsp;LIKE&nbsp;'%".<var>$this</var>-&gt;db-&gt;escape_like($search)."%'";`

**Note:** You don't need to **$this-&gt;escape_like** function when you use active record class because of [active record class](/user_guide/en/1.0.1/active-record-class.html) use auto escape foreach like condition.

`<var>$query</var>&nbsp;=&nbsp;<var>$this</var>-&gt;db-&gt;select("*")
-&gt;like('article','%%blabla')
-&gt;or_like('article',&nbsp;'blabla')
-&gt;get('articles');

<kbd>echo</kbd>&nbsp;<var>$this</var>-&gt;db-&gt;last_query();

<span class="output">//&nbsp;Output
</br&gt;></span>`

However when you use **query bind** for **like operators** you must use **$this-&gt;escape_like** function like this

`<var>$this</var>-&gt;db-&gt;prep()&nbsp;&nbsp;&nbsp;&nbsp;<span class="output">//&nbsp;tell&nbsp;to&nbsp;db&nbsp;class&nbsp;use&nbsp;pdo&nbsp;prepare()</span>
-&gt;select("*")
-&gt;like('article',":like")
-&gt;get('articles');

$value&nbsp;=&nbsp;"%%%some";
<var>$this</var>-&gt;db-&gt;exec(array(':like'&nbsp;=&gt;&nbsp;<var>$this</var>-&gt;db-&gt;escape_like($value)));
<var>$this</var>-&gt;db-&gt;fetch_all(<span class="lib">[assoc](/user_guide/en/1.0.1/generating-pdo-query-results.html)</span>);

<kbd>echo</kbd>&nbsp;<var>$this</var>-&gt;db-&gt;last_query();

<span class="output">//&nbsp;Output
</br&gt;></span>`

# Query Bindings

Obullo offers PDO bindValue and bindParam functionality, using bind operations will help you for the performance and security:

### Bind Types

<table border="0" class="tableborder" style="width: 100%;">
<tbody>
<tr>
  <th>Obullo Friendly Constant</th>
  <th>PDO CONSTANT</th>
  <th>Description</th>
</tr>

<tr>
  <td class="td">**param_bool**</td>
  <td class="td">PDO::PARAM_BOOL</td>
  <td class="td">Boolean</td>
</tr>

<tr>
  <td class="td">**param_null**</td>
  <td class="td">PDO::PARAM_NULL</td>
  <td class="td">NULL</td>
</tr>

<tr>
  <td class="td">**param_int**</td>
  <td class="td">PDO::PARAM_INT</td>
  <td class="td">Integer</td>
</tr>

<tr>
  <td class="td">**param_str**</td>
  <td class="td">PDO::PARAM_STR</td>
  <td class="td">String</td>
</tr>

<tr>
  <td class="td">**param_lob**</td>
  <td class="td">PDO::PARAM_LOB</td>
  <td class="td">Large Object Data (LOB)</td>
</tr>

</tbody>
</table>

### Bind Value Example

### $this-&gt;db-&gt;bind_value(<var>$paramater</var>,  <var>$value</var>,  <var>$data_type</var>)

`<var>$this</var>-&gt;db-&gt;prep();&nbsp;&nbsp;&nbsp;<span class="output">//&nbsp;tell&nbsp;to&nbsp;db&nbsp;class&nbsp;use&nbsp;pdo&nbsp;prepare&nbsp;</span>
<var>$this</var>-&gt;db-&gt;query("SELECT&nbsp;*&nbsp;FROM&nbsp;articles&nbsp;WHERE&nbsp;article_id=:id&nbsp;OR&nbsp;link=:code");

<var>$this</var>-&gt;db-&gt;bind_value(':id',&nbsp;1,&nbsp;**param_int**);&nbsp;&nbsp;<span class="output">//&nbsp;Integer&nbsp;</span>
<var>$this</var>-&gt;db-&gt;bind_value(':code',&nbsp;'i&nbsp;see&nbsp;dead&nbsp;people',&nbsp;**param_str**);&nbsp;<span class="output">//&nbsp;String&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<var>$this</var>-&gt;db-&gt;exec();&nbsp;&nbsp;<span class="output">//&nbsp;execute&nbsp;query</span>
$a&nbsp;=&nbsp;<var>$this</var>-&gt;db-&gt;fetch(<span class="lib">[assoc](/user_guide/en/1.0.1/generating-pdo-query-results.html)</span>);

print_r($a);`

The **double dots** in the query are automatically replaced with the values of **bind_value** functions.

### Bind Param Example

### $this-&gt;db-&gt;bind_param(<var>$paramater</var>,  <var>$variable</var>,  <var>$data_type</var>,  <var>$data_length</var>,  <var>$driver_options = array()</var>)

`<var>$this</var>-&gt;db-&gt;prep();&nbsp;&nbsp;&nbsp;<span class="output">//&nbsp;tell&nbsp;to&nbsp;db&nbsp;class&nbsp;use&nbsp;pdo&nbsp;prepare&nbsp;</span>
<var>$this</var>-&gt;db-&gt;query("SELECT&nbsp;*&nbsp;FROM&nbsp;articles&nbsp;WHERE&nbsp;article_id=:id&nbsp;OR&nbsp;link=:code");

<var>$this</var>-&gt;db-&gt;bind_param(':id',&nbsp;1,&nbsp;**param_int**,&nbsp;11);&nbsp;&nbsp;<span class="output">&nbsp;//&nbsp;Integer&nbsp;</span>
<var>$this</var>-&gt;db-&gt;bind_param(':code',&nbsp;'i&nbsp;see&nbsp;dead&nbsp;people',&nbsp;**param_str**,&nbsp;20);&nbsp;<span class="output">//&nbsp;String&nbsp;(int&nbsp;Length)&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<var>$this</var>-&gt;db-&gt;exec();&nbsp;&nbsp;<span class="output">//&nbsp;execute&nbsp;query</span>
$a&nbsp;=&nbsp;<var>$this</var>-&gt;db-&gt;fetch(<span class="lib">[assoc](/user_guide/en/1.0.1/generating-pdo-query-results.html)</span>);

print_r($a);`

The **double dots** in the query are automatically replaced with the values of **bind_param** functions.

The secondary benefit of using binds is that the values are automatically escaped, producing safer queries.  You don't have to remember to manually escape data; the engine does it automatically for you.

**A Short Way .. **

`<var>$this</var>-&gt;db-&gt;prep();&nbsp;&nbsp;&nbsp;
<var><var>$query</var></var>&nbsp;=&nbsp;<var>$this</var>-&gt;db-&gt;query("SELECT&nbsp;*&nbsp;FROM&nbsp;articles&nbsp;WHERE&nbsp;article_id=:id&nbsp;OR&nbsp;link=:code");

<var><var>$query</var></var>-&gt;bind_value(':id',&nbsp;1,&nbsp;**param_int**);&nbsp;&nbsp;
<var><var>$query</var></var>-&gt;bind_value(':code',&nbsp;'i-see-dead-people',&nbsp;**param_str**);&nbsp;

<var><var>$query</var></var>-&gt;exec();
$a&nbsp;=&nbsp;<var><var>$query</var></var>-&gt;fetch(<span class="lib">[assoc](/user_guide/en/1.0.1/generating-pdo-query-results.html)</span>);&nbsp;
print_r($a);`

### Automatically Bind Query

`<var>$this</var>-&gt;db-&gt;prep();&nbsp;&nbsp;
<var>$this</var>-&gt;db-&gt;query("SELECT&nbsp;*&nbsp;FROM&nbsp;articles&nbsp;WHERE&nbsp;article_id=:id&nbsp;OR&nbsp;link=:code");

$values[':id']&nbsp;&nbsp;&nbsp;=&nbsp;'1';
$values[':code']&nbsp;=&nbsp;'i&nbsp;see&nbsp;dead&nbsp;people';

<var>$this</var>-&gt;db-&gt;exec($values);

$a&nbsp;=&nbsp;<var>$this</var>-&gt;db-&gt;fetch(<span class="lib">[assoc](/user_guide/en/1.0.1/generating-pdo-query-results.html)</span>);
print_r($a);`

**Important:** Obullo does not support Question Mark binding at this time. 

&nbsp;

<div id="disqus_thread"><iframe frameborder="0" width="100%" scrolling="no" id="dsq1" data-disqus-uid="1" allowtransparency="true" role="complementary" style="width: 100%; border: medium none; overflow: hidden; height: 339px;" src="http://disqus.com/embed/comments/?f=obullo&amp;t_u=http%3A%2F%2Fwww.obullo.com%2Fuser_guide%2Fen%2F1.0.1%2Frunning-and-escaping-queries.html&amp;t_d=%0A1.0.1%20%2F%20Chapters%20%2F%20Database%20%2F%20Running%20and%20Escaping%20Queries&amp;t_t=%0A1.0.1%20%2F%20Chapters%20%2F%20Database%20%2F%20Running%20and%20Escaping%20Queries&amp;s_o=default&amp;disqus_version=1376588585#1" horizontalscrolling="no" verticalscrolling="no"></iframe></div>

    