//const server = "http://kognition.ihostfull.com/";
const server = "http://localhost/";

//add this. to _ons

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


/*
function(){
		xml_get_request("api/"class"/"function"", {params}, _on_function());
	}

	_on_function(){
		if (DEBUG) {console.log(data);}
	}
*/

function xml_get_request(end_point, params, ...callbacks){
	var xhttp = new XMLHttpRequest();

	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			for (var callback in callbacks)
			{
				console.log(callbacks[callback]);
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
	constructor(name,class_pin){
		this.name=name;
		this.hash=null;
		this.class_pin=class_pin;
	}

	create(callback=null){
		xml_get_request("api/member/create", {MEMBER_NAME: this.name, CLASS_PIN: this.class_pin}, this._on_create, callback);
	}

	_on_create(data){
		if (DEBUG) {console.log(data);}
	}


	edit(new_member_name){
		xml_get_request("api/member/edit", {MEMBER_HASH: this.hash, MEMBER_NAME: new_member_name}, this._on_edit);
	}

	_on_edit(data){
		if (DEBUG) {console.log(data);}
	}


	leave(){
		xml_get_request("api/member/delete", {MEMBER_HASH: this.hash}, this._on_leave);
	}

	_on_leave(data){
		if (DEBUG) {console.log(data);}
	}


	get_questions(callback=null){
		xml_get_request("api/member/get_questions", {MEMBER_HASH: this.hash}, this._on_get_questions, callback);
	}

	_on_get_questions(data){
		if (DEBUG) {console.log(data);}
	}


	update(){
		xml_get_request("api/member/update", {MEMBER_HASH: this.hash}, this._on_update);
	}

	_on_update(data){
		if (DEBUG) {console.log(data);}
	}
};

class Host{
	constructor(name,class_name){
		this.name=name;
		this.hash=null;
		this.class_name=class_name;
	}

	create(){
		xml_get_request("api/host/create", {HOST_NAME: this.name, CLASS_NAME: this.class_name}, this._on_create);
	}

	_on_create(data){
		if (DEBUG) {console.log(data);}
	}


	edit(new_host_name){
		xml_get_request("api/host/edit", {HOST_HASH: this.host_hash, HOST_NAME: new_host_name}, this._on_edit);
	}

	_on_edit(data){
		if (DEBUG) {console.log(data);}
	}


	end_host(){
		xml_get_request("api/host/delete", {HOST_HASH: this.host_hash}, this._on_end_host);
	}

	_on_end_host(data){
		if (DEBUG) {console.log(data);}
	}


	kick(member_hash){
		xml_get_request("api/host/kick", {HOST_HASH: this.hash, MEMBER_HASH: member_hash}, this._on_kick);
	}

	_on_kick(data){
		if (DEBUG) {console.log(data);}
	}


	get_questions(){
		xml_get_request("api/host/get_questions", {HOST_HASH: this.hash}, this._on_get_questions);
	}

	_on_get_questions(data){
		if (DEBUG) {console.log(data);}
	}


	get_answers(question_hash){
		xml_get_request("api/host/get_answers", {HOST_HASH: this.hash, QUESTION_HASH: question_hash}, this._on_get_answers);
	}

	_on_get_answers(data){
		if (DEBUG) {console.log(data);}
	}


	update(show_members=null){
		xml_get_request("api/host/update", {HOST_HASH: this.hash, SHOW_MEMBERS: show_members}, this._on_update);
	}

	_on_update(data){
		if (DEBUG) {console.log(data);}
	}
}

class Question{
	constructor(question_hash,host_hash,question_text,class_pin,answer_mode,name=null,closed=false){//may add questionImage later
		this.question_hash=question_hash;
		this.host_hash=host_hash;
		this.name=name;
		this.question_text=question_text;
		this.class_pin=class_pin;
		this.answer_mode=answer_mode;
		this.closed=closed;
	}


	create(){
		xml_get_request("api/question/create", {HOST_HASH: this.host_hash, QUESTION_TEXT: this.question_text, ANSWER_MODE: this.answer_mode, QUESTION_NAME: this.name}, this._on_create);
	}

	_on_create(data){
		if (DEBUG) {console.log(data);}
	}


	delete_question(){
		xml_get_request("api/question/delete", {HOST_HASH: this.host_hash, QUESTION_HASH: this.question_hash}, this._on_delete_question);
	}

	_on_delete_question(data){
		if (DEBUG) {console.log(data);}
	}


	edit(new_name=null,new_question_text=null,closed=null){
		xml_get_request("api/question/edit", {HOST_HASH: this.host_hash, QUESTION_HASH: this.question_hash, QUESTION_NAME: new_name, QUESTION_TEXT: new_question_text, QUESTION_CLOSED: closed}, this._on_edit);
	}

	_on_edit(data){
		if (DEBUG) {console.log(data);}
	}


	update(){
		xml_get_request("api/question/update", {HOST_HASH: this.host_hash, QUESTION_HASH: this.question_hash}, this._on_update);
	}

	_on_update(data){
		if (DEBUG) {console.log(data);}
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
	}

	edit(){
		xml_get_request("api/class/edit", {}, this._on_edit);//documentation lost
	}

	_on_edit(data){
		if (DEBUG) {console.log(data);}
	}


	update(host_hash){
		xml_get_request("api/class/update", {CLASS_PIN: this.class_pin, HOST_HASH: host_hash}, this._on_update);
	}

	_on_update(data){
		if (DEBUG) {console.log(data);}
	}


	ping_class(){
		xml_get_request("api/class/ping", {CLASS_PIN: this.class_pin}, this._on_ping_class);
	}

	_on_ping_class(data){
		if (DEBUG) {console.log(data);}
	}
}

class Server{
	constructor(){
		this._api_last = null
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
		this.qestion_hash=question_hash;
		this.exists=exists;
		this._api_last = null;
		this.answer_hash = null;
		//this.timestamp;
	}


	create(callback=null){
		xml_get_request("api/answer/create", {MEMBER_HASH: this.member_hash, QUESTION_HASH: this.question_hash, ANSWER_TINYMCE: this.answer_tinymce}, this._on_create, callback);//tiny mce
	}

	_on_create(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
		if (data['success'])
		{
			this.exists = true;
		}

	}


	delete_answer(member_hash, callback=null){
		xml_get_request("api/answer/delete", {ANSWER_HASH: this.hash, MEMBER_HASH: member_hash}, this._on_delete_answer, callback);
	}

	_on_delete_answer(data){
		if (DEBUG) {console.log(data);}
		this._api_last = data;
		if (data['success'])
		{
			this.exists = false;
		}
	}


	edit(answer_tinymce=null){
		xml_get_request("api/answer/edit", {ANSWER_HASH: this.hash, MEMBER_HASH: this.member_hash, ANSWER_TINYMCE: answer_tinymce}, this._on_edit);
	}

	_on_edit(data){
		if (DEBUG) {console.log(data);}
	}


	update(member_hash){
		xml_get_request("api/answer/update", {ANSWER_HASH: this.hash, MEMBER_HASH: member_hash}, this._on_update);
	}

	_on_update(data){
		if (DEBUG) {console.log(data);}
	}
}
