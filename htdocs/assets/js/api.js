//const server = "http://kognition.ihostfull.com/";
const server = "http://localhost/";

//Server - find_classes
//class - ping_class

const HOST_HASH="host_hash";
const CLASS_PIN="class_pin";
const HOST_NAME="host_name";
const CLASS_NAME="class_name";
const CLASS_CLOSED="class_closed";
const QUESTION_CLOSED="question_closed";
const MEMBER_NAME="member_name";
const MEMBER_HASH="member_hash";
const QUESTION_HASH="question_hash";
const SHOW_MEMBERS="show_members";
const ANSWER_TINYMCE="answer_tinymce";
const QUESTION_TEXT="question_text";
const QUESTION_NAME="question_name";
const ANSWER_MODE="answer_mode";
const ANSWER_HASH="answer_hash";
const TOTAL="total";
const DEBUG = false;

const ANSWER_MODE_TINYMCE="tinymce";

/*
function(){
		xml_get_request("api/"class"/"function"", {params}, _on_function());
	}

	_on_function(){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
	}
*/

function xml_get_request(end_point, params, ...callbacks){
	var xhttp = new XMLHttpRequest();

	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			for (var callback in callbacks)
			{
				//console.log(callbacks[callback]);
				if (callbacks[callback])
				{
					callbacks[callback](JSON.parse(xhttp.responseText));
				}
			}
		}
	}

	xhttp.open("GET", server + end_point + "?" + url_encode(params), true);
	xhttp.send();
}

function url_encode(params){
	var encoded = [];

	for (var key in params) {
		encoded.push(key.toLowerCase() + '=' + encodeURIComponent(params[key]));
	}

	return encoded.join('&');
}

class Member{
	//var self = this;

	constructor(name,class_pin){
		this.exists=false;
		this.name=name;
		this.hash=null;
		this.class_pin=class_pin;
		this._api_last = null;

		this.self = this;
	}

	create(callback=null){
		//xml_get_request("api/member/create", {MEMBER_NAME: this.name, CLASS_PIN: this.class_pin}, this._on_create, callback);
		xml_get_request("api/member/create", {MEMBER_NAME: this.name, CLASS_PIN: this.class_pin}, this._on_update, callback);
	}

	/*_on_create(data){
		if (DEBUG) {console.log(data);}
		//console.log(this);
		this._api_last = data;
		if(data["success"]){
			//this.hash=data['member']['member_hash'];
			//this.exists=true;
			self._on_update(data);
		}
	}*/


	edit(new_member_name, callback=null){
		xml_get_request("api/member/edit", {MEMBER_HASH: this.hash, MEMBER_NAME: new_member_name}, this._on_edit, callback);
	}

	_on_edit(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
		if(data["success"]){
			this._on_update(data);
		}
	}


	leave(callback=null){
		xml_get_request("api/member/delete", {MEMBER_HASH: this.hash}, this._on_leave, callback);
	}

	_on_leave(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
		if(data["success"]){
			this.exists=false;
		}
	}


	get_questions(callback=null){
		xml_get_request("api/member/get_questions", {MEMBER_HASH: this.hash}, this._on_get_questions, callback);
	}

	_on_get_questions(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
	}


	update(callback=null){
		xml_get_request("api/member/update", {MEMBER_HASH: this.hash}, this._on_update, callback);
	}

	_on_update(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
		if(data["success"]){
			this.exists=data['member']['exists'];
			this.name=data['member']['member_name'];
			this.hash=data['member']['member_hash'];
			this.class_pin=['class']['class_pin'];
		}
	}
};

class Host{
	constructor(name,class_name){
		this.exists=false;
		this.name=name;
		this.hash=null;
		this.class_name=class_name;
		this._api_last = null;
		this.members = {};
		this.questions = {};
		//NEEDS: this.questions?
	}

	create(callback=null){
		//xml_get_request("api/host/create", {HOST_NAME: this.name, CLASS_NAME: this.class_name}, this._on_create, callback);
		xml_get_request("api/host/create", {HOST_NAME: this.name, CLASS_NAME: this.class_name}, this._on_update, callback);
	}

	/*_on_create(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
		if(data["success"]){
			this._on_update(data);
		}
	}*/


	edit(new_host_name, callback=null){
		xml_get_request("api/host/edit", {HOST_HASH: this.host_hash, HOST_NAME: new_host_name}, this._on_edit, callback);
	}

	_on_edit(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
		if(data["success"]){
			this._on_update(data);
		}
	}


	end_host(callback=null){
		xml_get_request("api/host/delete", {HOST_HASH: this.host_hash}, this._on_end_host, callback);
	}

	_on_end_host(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
	}


	kick(member_hash, callback=null){
		xml_get_request("api/host/kick", {HOST_HASH: this.hash, MEMBER_HASH: member_hash}, this._on_kick, callback);
	}

	_on_kick(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
	}


	get_questions(callback=null){
		xml_get_request("api/host/get_questions", {HOST_HASH: this.hash}, this._on_get_questions, callback);
	}

	_on_get_questions(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
		if(data["success"]){
			if ('questions' in data)
			{
				this.questions = data['questions'];
			}
		}
	}


	get_answers(question_hash, callback=null){
		xml_get_request("api/host/get_answers", {HOST_HASH: this.hash, QUESTION_HASH: question_hash}, this._on_get_answers, callback);
	}

	_on_get_answers(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
	}


	update(show_members=null, callback=null){
		xml_get_request("api/host/update", {HOST_HASH: this.hash, SHOW_MEMBERS: show_members}, this._on_update, callback);
	}

	_on_update(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
		if(data["success"]){
			this.exists=data['host']['exists'];
			this.name=data['host']['host_name'];
			this.hash=data['host']['host_hash'];
			this.class_name=data['class']['class_name'];

			if ('members' in data['class'])
			{
				this.members= data['class']['members'];
			}
		}
	}
}

class Question{
	constructor(question_hash,host_hash,question_text,class_pin,answer_mode,name=null,closed=false){//may add questionImage later
		this.exists=false;
		this.question_hash=question_hash;
		this.host_hash=host_hash;
		this.name=name;
		this.question_text=question_text;
		this.class_pin=class_pin;
		this.answer_mode=answer_mode;
		this.closed=closed;
		this._api_last = null;
	}

	get_answer(member_hash, callback=null)
	{
		xml_get_request("api/question/get_answer", {MEMBER_HASH: member_hash, QUESTION_HASH: this.question_hash}, callback);
	}

	create(callback=null){
		//xml_get_request("api/question/create", {HOST_HASH: this.host_hash, QUESTION_TEXT: this.question_text, ANSWER_MODE: this.answer_mode, QUESTION_NAME: this.name}, this._on_create, callback);
		xml_get_request("api/question/create", {HOST_HASH: this.host_hash, QUESTION_TEXT: this.question_text, ANSWER_MODE: this.answer_mode, QUESTION_NAME: this.name}, this._on_update, callback);
	}

	/*_on_create(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
		if(data["success"]){
			this._on_update(data);
		}
	}*/


	delete_question(callback=null){
		xml_get_request("api/question/delete", {HOST_HASH: this.host_hash, QUESTION_HASH: this.question_hash}, this._on_delete_question, callback);
	}

	_on_delete_question(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
	}


	edit(new_name=null,new_question_text=null,closed=null, callback=null){
		xml_get_request("api/question/edit", {HOST_HASH: this.host_hash, QUESTION_HASH: this.question_hash, QUESTION_NAME: new_name, QUESTION_TEXT: new_question_text, QUESTION_CLOSED: closed}, this._on_edit, callback);
	}

	_on_edit(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
		if(data["success"]){
			this._on_update(data);
		}
	}


	update(callback=null){
		xml_get_request("api/question/update", {HOST_HASH: this.host_hash, QUESTION_HASH: this.question_hash}, this._on_update, callback);
	}

	_on_update(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
		if(data["success"]){
			this.question_hash=data['question']['question_hash'];
			this.host_hash=data['host']['host_hash'];
			this.name=data['question']['question_name'];
			this.question_text=data['question']['question_text'];
			this.class_pin=data['class']['class_pin'];
			this.answer_mode=data['question']['answer_mode'];
			this.closed=data['question']['closed'];
			this.exists=data['question']['exists'];
		}
	}
}

class Class{
	//var class_name;
	//var class_pin;

	constructor(class_name,class_pin,host_hash,exists=false,closed=false){
		this.class_name=class_name;
		this.class_pin=class_pin;
		this.closed=closed;
		this.host_hash=host_hash;
		this.exists=exists;
		this._api_last = null;
	}

	edit(host_hash, new_class_name=null, new_closed=null, new_class_public=null, callback=null){
		xml_get_request("api/class/edit", {CLASS_PIN: this.class_pin, HOST_HASH: host_hash, CLASS_NAME: new_class_name, CLOSED: new_closed, CLASS_PUBLIC: new_class_public}, this._on_edit, callback);
	}

	_on_edit(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
		if(data["success"]){
			this._on_update(data);
		}
	}


	update(host_hash, callback=null){
		xml_get_request("api/class/update", {CLASS_PIN: this.class_pin, HOST_HASH: host_hash}, this._on_update, callback);
	}

	_on_update(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
		if(data["success"]){
			this.class_name=data['class']['class_name'];
			this.class_pin=data['class']['class_pin'];
			this.closed=data['class']['closed'];
			this.host_hash=data['host']['host_hash'];
			this.exists=data['class']['exists'];
		}
	}


	ping_class(callback=null){
		xml_get_request("api/class/ping", {CLASS_PIN: this.class_pin}, this._on_ping_class, callback);
	}

	_on_ping_class(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
	}
}

class Server{
	constructor(){
		this._api_last = null;
	}


	find_classes(total=null, callback=null){
		xml_get_request("api/server/find_classes", {TOTAL: total}, this._on_find_classes, callback);
	}

	_on_find_classes(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
	}



}

class Answer{
	constructor(member_hash,question_hash,answer_tinymce=null,exists=false){
		this.member_hash=member_hash;
		this.answer_tinymce=answer_tinymce;
		this.question_hash=question_hash;
		this.exists=exists;
		this._api_last = null;
		this.answer_hash = null;
		//this.timestamp;

		//this.self = this;
	}


	create(callback=null){
		//xml_get_request("api/answer/create", {MEMBER_HASH: this.member_hash, QUESTION_HASH: this.question_hash, ANSWER_TINYMCE: this.answer_tinymce}, this._on_create, callback);//tiny mce
		xml_get_request("api/answer/create", {MEMBER_HASH: this.member_hash, QUESTION_HASH: this.question_hash, ANSWER_TINYMCE: this.answer_tinymce}, this._on_update, callback); //hardcoded?
	}

	/*_on_create(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
		if(data["success"]){
			this._on_update(data);
		}
	}*/


	delete_answer(member_hash, callback=null){
		xml_get_request("api/answer/delete", {ANSWER_HASH: this.answer_hash, MEMBER_HASH: member_hash}, this._on_delete_answer, callback);
	}

	_on_delete_answer(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
		if (data['success'])
		{
			this.exists = false;
		}
	}


	edit(answer_tinymce=null, callback=null){
		xml_get_request("api/answer/edit", {ANSWER_HASH: this.answer_hash, MEMBER_HASH: this.member_hash, ANSWER_TINYMCE: answer_tinymce}, this._on_update, callback);
	}

	_on_edit(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
		if(data["success"]){
			this._on_update(data);
		}
	}


	update(member_hash, callback=null){
		xml_get_request("api/answer/update", {ANSWER_HASH: this.answer_hash, MEMBER_HASH: member_hash}, this._on_update, callback);
	}

	_on_update(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
		if(data["success"]){
			this.member_hash=data['member']['member_hash'];
			this.answer_tinymce=data['answer']['answer_tinymce'];
			this.qestion_hash=data['question']['question_hash'];
			this.exists=data['answer']['exists'];
			this.answer_hash=data['answer']['answer_hash'];
		}

		//console.log(this);
	}
}
