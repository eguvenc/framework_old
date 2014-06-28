### Reserved Names <a name="reserved-names"></a>

------

In order to help out, Framework uses a series of functions and names in its operation. Because of this, some names cannot be used by a developer. Following is a list of reserved names that cannot be used.

### Class Names & Methods <a name="controller-names"></a>

------

Since your controller classes will extend the main application controller you must be careful not to name your functions identically to the ones used by that class, otherwise your local functions will override them. The following is a list of reserved names. Do not name your controller functions any of these:

- _getInstance()
- _remap()
- _response()
- index()
- Error
- Exceptions
- Controller
- Config
- Db
- Database
- Pdo_Adapter
- Hooks
- Hmvc
- Model
- Model_Trait
- Get
- Post
- Request
- Response
- Router
- Uri
- Odm

#### Functions

------

- autoloader()
- getConfig()
- getInstance()
- getStatic()
- hasTranslate()
- translate()
- packageExists()
- removeInvisibleCharacters()
- runFramework()

Look at your <kbd>obullo.php</kbd> file located in your <b>Obullo Package</b>.

#### Controller Variables

------

- $this->config
- $this->router
- $this->uri
- $this->response
- $this->translator

#### Constants

------

Look at your <kbd>constants</kbd> file located in your project root.

##### Database Constants

------

Database constants located in <kbd>database_pdo</kbd> package <kbd>database_layer.php</kbd> file.