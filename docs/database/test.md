

#  Running and Escaping Queries

## Direct Query

To submit a query, use the following function:

`$this-&gt;db-&gt;query('YOUR&nbsp;QUERY&nbsp;HERE');`

The query() function returns a database result **object** when "read" type queries are run, which you can use to [show your results][1]. When retrieving data you will typically assign the query to your own variable, like this:

`$query&nbsp;=&nbsp;$this-&gt;db-&gt;query('YOUR&nbsp;QUERY&nbsp;HERE');`

## PDO Exec Query

This query type same as direct query just it returns to $affected_rows automatically. You should use **exec_query** function for INSERT, UPDATE, DELETE operations.

`$affected_rows&nbsp;=&nbsp;$this-&gt;db-&gt;exec_query('INSERT&nbsp;QUERY');&nbsp;

echo&nbsp;$affected_rows;&nbsp;&nbsp;&nbsp;//output&nbsp;&nbsp;1

`

It's a very good security practice to escape your data before submitting it into your database. Obullo has three methods that help you do this:

### $this-&gt;db-&gt;escape()

This function determines the data type so that it can escape only string data. It also automatically adds single quotes around the data and it can automatically determine data types. `$sql&nbsp;=&nbsp;"INSERT&nbsp;INTO&nbsp;table&nbsp;(title)&nbsp;VALUES(".$this-&gt;db-&gt;escape(**(string)**$title).")";`

Supported data types: (int), (string), (boolean)

### $this-&gt;escape_str();

This function escapes the data passed to it, regardless of type. Most of the time you'll use the above function rather than this one. Use the function like this:

`$$sql&nbsp;=&nbsp;"INSERT&nbsp;INTO&nbsp;table&nbsp;(title)&nbsp;VALUES('".$this-&gt;db-&gt;escape_str($title)."')";`

### $this-&gt;db-&gt;escape_like()

This method should be used when strings are to be used in LIKE conditions so that LIKE wildcards ('%', '_') in the string are also properly escaped. `$search&nbsp;=&nbsp;'20%&nbsp;raise';
$sql&nbsp;=&nbsp;"SELECT&nbsp;id&nbsp;FROM&nbsp;table&nbsp;WHERE&nbsp;column&nbsp;LIKE&nbsp;'%".$this-&gt;db-&gt;escape_like($search)."%'";`

**Note:** You don't need to **$this-&gt;escape_like** function when you use active record class because of [active record class][2] use auto escape foreach like condition.

`$query&nbsp;=&nbsp;$this-&gt;db-&gt;select("*")
-&gt;like('article','%%blabla')
-&gt;or_like('article',&nbsp;'blabla')
-&gt;get('articles');

echo&nbsp;$this-&gt;db-&gt;last_query();

//&nbsp;Output


`

However when you use **query bind** for **like operators** you must use **$this-&gt;escape_like** function like this

`$this-&gt;db-&gt;prep()&nbsp;&nbsp;&nbsp;&nbsp;//&nbsp;tell&nbsp;to&nbsp;db&nbsp;class&nbsp;use&nbsp;pdo&nbsp;prepare()
-&gt;select("*")
-&gt;like('article',":like")
-&gt;get('articles');

$value&nbsp;=&nbsp;"%%%some";
$this-&gt;db-&gt;exec(array(':like'&nbsp;=&gt;&nbsp;$this-&gt;db-&gt;escape_like($value)));
$this-&gt;db-&gt;fetch_all([assoc][1]);

echo&nbsp;$this-&gt;db-&gt;last_query();

//&nbsp;Output


`

Obullo offers PDO bindValue and bindParam functionality, using bind operations will help you for the performance and security:

### Bind Types

Obullo Friendly Constant PDO CONSTANT Description

**param_bool**
PDO::PARAM_BOOL
Boolean

**param_null**
PDO::PARAM_NULL
NULL

**param_int**
PDO::PARAM_INT
Integer

**param_str**
PDO::PARAM_STR
String

**param_lob**
PDO::PARAM_LOB
Large Object Data (LOB)

### Bind Value Example

### $this-&gt;db-&gt;bind_value($paramater, $value, $data_type)

`$this-&gt;db-&gt;prep();&nbsp;&nbsp;&nbsp;//&nbsp;tell&nbsp;to&nbsp;db&nbsp;class&nbsp;use&nbsp;pdo&nbsp;prepare&nbsp;
$this-&gt;db-&gt;query("SELECT&nbsp;*&nbsp;FROM&nbsp;articles&nbsp;WHERE&nbsp;article_id=:id&nbsp;OR&nbsp;link=:code");

$this-&gt;db-&gt;bind_value(':id',&nbsp;1,&nbsp;**param_int**);&nbsp;&nbsp;//&nbsp;Integer&nbsp;
$this-&gt;db-&gt;bind_value(':code',&nbsp;'i&nbsp;see&nbsp;dead&nbsp;people',&nbsp;**param_str**);&nbsp;//&nbsp;String&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

$this-&gt;db-&gt;exec();&nbsp;&nbsp;//&nbsp;execute&nbsp;query
$a&nbsp;=&nbsp;$this-&gt;db-&gt;fetch([assoc][1]);

print_r($a);

`

The **double dots** in the query are automatically replaced with the values of **bind_value** functions.

### Bind Param Example

### $this-&gt;db-&gt;bind_param($paramater, $variable, $data_type, $data_length, $driver_options = array())

`$this-&gt;db-&gt;prep();&nbsp;&nbsp;&nbsp;//&nbsp;tell&nbsp;to&nbsp;db&nbsp;class&nbsp;use&nbsp;pdo&nbsp;prepare&nbsp;
$this-&gt;db-&gt;query("SELECT&nbsp;*&nbsp;FROM&nbsp;articles&nbsp;WHERE&nbsp;article_id=:id&nbsp;OR&nbsp;link=:code");

$this-&gt;db-&gt;bind_param(':id',&nbsp;1,&nbsp;**param_int**,&nbsp;11);&nbsp;&nbsp;&nbsp;//&nbsp;Integer&nbsp;
$this-&gt;db-&gt;bind_param(':code',&nbsp;'i&nbsp;see&nbsp;dead&nbsp;people',&nbsp;**param_str**,&nbsp;20);&nbsp;//&nbsp;String&nbsp;(int&nbsp;Length)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

$this-&gt;db-&gt;exec();&nbsp;&nbsp;//&nbsp;execute&nbsp;query
$a&nbsp;=&nbsp;$this-&gt;db-&gt;fetch([assoc][1]);

print_r($a);

`

The **double dots** in the query are automatically replaced with the values of **bind_param** functions.

The secondary benefit of using binds is that the values are automatically escaped, producing safer queries. You don't have to remember to manually escape data; the engine does it automatically for you.

**A Short Way .. **

`$this-&gt;db-&gt;prep();&nbsp;&nbsp;&nbsp;
$query&nbsp;=&nbsp;$this-&gt;db-&gt;query("SELECT&nbsp;*&nbsp;FROM&nbsp;articles&nbsp;WHERE&nbsp;article_id=:id&nbsp;OR&nbsp;link=:code");

$query-&gt;bind_value(':id',&nbsp;1,&nbsp;**param_int**);&nbsp;&nbsp;
$query-&gt;bind_value(':code',&nbsp;'i-see-dead-people',&nbsp;**param_str**);&nbsp;

$query-&gt;exec();
$a&nbsp;=&nbsp;$query-&gt;fetch([assoc][1]);&nbsp;
print_r($a);

`

### Automatically Bind Query

`$this-&gt;db-&gt;prep();&nbsp;&nbsp;
$this-&gt;db-&gt;query("SELECT&nbsp;*&nbsp;FROM&nbsp;articles&nbsp;WHERE&nbsp;article_id=:id&nbsp;OR&nbsp;link=:code");

$values[':id']&nbsp;&nbsp;&nbsp;=&nbsp;'1';
$values[':code']&nbsp;=&nbsp;'i&nbsp;see&nbsp;dead&nbsp;people';

$this-&gt;db-&gt;exec($values);

$a&nbsp;=&nbsp;$this-&gt;db-&gt;fetch([assoc][1]);
print_r($a);

`

**Important:** Obullo does not support Question Mark binding at this time.

&nbsp;

Please enable JavaScript to view the [comments powered by Disqus.][3] [comments powered by ][4]

   [1]: http://www.obullo.com/user_guide/en/1.0.1/generating-pdo-query-results.html
   [2]: http://www.obullo.com/user_guide/en/1.0.1/active-record-class.html
   [3]: http://disqus.com/?ref_noscript
   [4]: http://disqus.com
  