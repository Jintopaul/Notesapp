Notes app api documentation

	Via Postman

For signup

	POST - http://127.0.0.1:8000/api/signup

	Body -> raw -> json -> {
							    "first_name": "Foo",
							    "last_name": "bar",
							    "email": "foo@gm.com",
							    "password": "12345678",
							    "address": "Kerala, India"
							}

For login
    
    POST - http://127.0.0.1:8000/api/login

    Body -> raw -> json -> {
							    "email": "foo@gm.com",
							    "password": "12345678"
							}


For logout 

	GET - http://127.0.0.1:8000/api/logout

	Headers -> Authorization : Bearer “token"



For user profile

	GET - http://127.0.0.1:8000/api/user

	Headers -> Authorization : Bearer “token"



For create notes

	POST - http://127.0.0.1:8000/api/notes

	Headers -> Authorization : Bearer “token"

	Body -> form-data
				[
					key- file (upload file)
					key- text  "Foo"
				]

For all notes

	GET - http://127.0.0.1:8000/api/notes

	Headers -> Authorization : Bearer “token"


For single note

	GET - http://127.0.0.1:8000/api/notes/2

	Headers -> Authorization : Bearer “token"


For update note

	put - http://127.0.0.1:8000/api/notes/2

	Headers -> Authorization : Bearer “token"


	Body -> raw -> json -> {
							    "text": "bar",
							}

For delete note

	delete - http://127.0.0.1:8000/api/notes/2
	
	Headers -> Authorization : Bearer “token"