
### Bind Constants

------

<table><thead><tr>
<th>Friendly Constant</th><th>PDO CONSTANT</th><th>Description</th></tr></thead><tbody>
<tr><td>PARAM_BOOL</td><td>PDO::PARAM_BOOL</td><td>Boolean</td></tr>
<tr><td>PARAM_NULL</td><td>PDO::PARAM_NULL</td><td>NULL</td></tr>
<tr><td>PARAM_INT</td><td>PDO::PARAM_INT</td><td>Integer</td></tr>
<tr><td>PARAM_STR</td><td>PDO::PARAM_STR</td><td>String</td></tr>
<tr><td>PARAM_LOB</td><td>PDO::PARAM_LOB</td><td>Large Object Data (LOB)</td></tr>
<tr><td>PARAM_INOUT</td><td>PDO::PARAM_INPUT_OUTPUT</td><td>integer</td></tr></tbody></table>

### Result Constants

------
    
Below the table show available result types.

<table>
    <thead>
        <tr>
            <th>Function</th>    
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>ASSOC</td>
            <td>Fetch query result as an associative array</td>
        </tr>
        <tr>
            <td>OBJ</td>
            <td>Fetch query result as object</td>
        </tr>
        <tr>
            <td>LAZY</td>
            <td>Fetch each row as an object with variable names that correspond to the column names.</td>
        </tr>
        <tr>
            <td>NAMED</td>
            <td>Fetch each row as an array indexed by column name</td>
        </tr>
        <tr>
            <td>NUM</td>
            <td>Fetch each row as an array indexed by column number</td>
        </tr>
        <tr>
            <td>BOTH</td>
            <td>Fetch each row as an array indexed by both column name and numbers</td>
        </tr>
        <tr>
            <td>BOUND</td>
            <td>Specifies that the fetch method shall return true and assign the values of the columns in the result set to the PHP variables to which they were bound with the PDO bindParam() or PDO bindColumn() methods.</td>
        </tr>
        <tr>
            <td>COLUMN</td>
            <td>Specifies that the fetch method shall return only a single requested column from the next row in the result set</td>
        </tr>
        <tr>
            <td>AS_CLASS</td>
            <td>Specifies that the fetch method shall return a new instance of the requested class, mapping the columns to named properties in the class.</td>
        </tr>
        <tr>
            <td>INTO</td>
            <td>Specifies that the fetch method shall update an existing instance of the requested class, mapping the columns to named properties in the class.</td>
        </tr>
        <tr>
            <td>KEY_PAIR</td>
            <td>Fetch into an array where the 1st column is a key and all subsequent columns are value. Note: Available since PHP 5.2.3</td>
        </tr>
        <tr>
            <td>CLASS_TYPE</td>
            <td>Determine the class name from the value of first column.</td>
        </tr>
        <tr>
            <td>SERIALIZE</td>
            <td>As into constant but object is provided as a serialized string.</td>
        </tr>
        <tr>
            <td>PROPS_LATE</td>
            <td>Note: Available since PHP 5.2.3</td>
        </tr>
        <tr>
            <td>FUNC</td>
            <td></td>
        </tr>
        <tr>
            <td>GROUP</td>
            <td></td>
        </tr>
        <tr>
            <td>UNIQUE</td>
            <td></td>
        </tr>
        <tr>
            <td>ORI_NEXT</td>
            <td>Fetch the next row in the result set. Valid only for scrollable cursors.</td>
        </tr>
        <tr>
            <td>ORI_PRIOR</td>
            <td>Fetch the previous row in the result set. Valid only for scrollable cursors.</td>
        </tr>
        <tr>
            <td>ORI_FIRST</td>
            <td>Fetch the first row in the result set. Valid only for scrollable cursors.</td>
        </tr>
        <tr>
            <td>ORI_LAST</td>
            <td>Fetch the last row in the result set. Valid only for scrollable cursors.</td>
        </tr>
        <tr>
            <td>ORI_ABS</td>
            <td>Fetch the requested row by row number from the result set. Valid only for scrollable cursors.</td>
        </tr>
        <tr>
            <td>ORI_REL</td>
            <td>Fetch the requested row by relative position from the current position of the cursor in the result set.</td>
        </tr>
    </tbody>
</table>

More details at [PDO Predefined Constants](http://php.net/manual/en/pdo.constants.php).