import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Router } from '@angular/router';
@Injectable({
  providedIn: 'root'
})
export class HttpService {
  apiUrl: string;
  headers: HttpHeaders;
  constructor(private http: HttpClient,private _router:Router) {
    this.headers = new HttpHeaders({
      Authorization: `Bearer ${localStorage.getItem('token')}`})
       
      //  this.apiUrl = "http://165.22.222.109:4000";
  //this.apiUrl = "http://139.59.65.18:4000";
  this.apiUrl = "http://localhost:4000";
  
   }

   /**
   * for using get method.
   * @param url : url where requei
   * @param params
   */
  get(url: string, params?: object) {
    this.headers = new HttpHeaders({
      Authorization: `Bearer ${localStorage.getItem('token')}`})
    const apiUrl = `${this.apiUrl}/${url}${this.generateQueryString(params)}`;
    console.log(apiUrl);
    return this.http.get(apiUrl, {
      headers: this.headers
    });
  }
  /**
   * for using put method
   * @param url : url where request will be send
   * @param data : body part of post data
   * @param params : Query params 
   */
  put(url: string, data?: any, params?: object) {
    this.headers = new HttpHeaders({
      Authorization: `Bearer ${localStorage.getItem('token')}`})
    //console.log(data);
    const apiUrl = `${this.apiUrl}/${url}${this.generateQueryString(params)}`;
    console.log(data);
    console.log(apiUrl);
    return this.http.put(apiUrl, data, {
      headers: this.headers
    });
  }
  /**
   * for using put method
   * @param url : url where request will be send
   * @param data : body part of post data
   * @param params : Query params 
   */
  post(url: string, data?: any, params?: object) {
    this.headers = new HttpHeaders({
      Authorization: `Bearer ${localStorage.getItem('token')}`})
    const apiUrl = `${this.apiUrl}/${url}${this.generateQueryString(params)}`;
    console.log(apiUrl);
    return this.http.post(apiUrl, data, {
      headers: this.headers
    });
  }
  /**
   * delete method does not have any body part
   * passes object id as parameter
   * also passes token in header part
   * @param url : url where request will be send
   */
  delete(url: string) {
    this.headers = new HttpHeaders({
      Authorization: `Bearer ${localStorage.getItem('token')}`})
    const apiUrl = `${this.apiUrl}/${url}`;
    return this.http.delete(apiUrl, {
      headers: this.headers
    });
  }
  /**
   * Helper Method that will generate the queryString.
   * @param params Object to be converted into URLSearchParam.
   */
  generateQueryString(params?: object): string {
    let queryString = '',
      httpParam = new URLSearchParams();
    Object.keys(params || {}).forEach(key => httpParam.set(key, params[key]));
    queryString = httpParam.toString() ? `?${httpParam.toString()}` : '';
    return queryString;
  }
  /**
   * Helper method to call the GET api for any url.
   * @param url string url that needs to be called.
   */
  simpleGet(url: string) {
    return this.http.get(`${this.apiUrl}/${url}`);
  }

  /**
   * Helper method to call the POST api for any url.
   * @param url string url that needs to be called.
   * @param data Object data that needs to be passed with API.
   */
  simplePost(url: string, data: any) {
    return this.http.post(`${this.apiUrl}/${url}`, data);
  }

  /**
   * Helper method to call the POST api for any url.
   * @param url string url that needs to be called.
   * @param data Object data that needs to be passed with API.
   */
  // constructor() { }

  loggedIn(){
    return !!localStorage.getItem('token')
  }

  logoutUser()
  {
    localStorage.removeItem('token');
    localStorage.removeItem('designation');
    localStorage.removeItem('emp_id');
    localStorage.removeItem('first_name');
    localStorage.removeItem('id');
    localStorage.removeItem('last_name');
    localStorage.removeItem('username');
    localStorage.removeItem('length');
   
    console.log(localStorage);
     this._router.navigate(['/login'])
    
  }

  
}
