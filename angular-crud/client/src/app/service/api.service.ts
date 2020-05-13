import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';

import {Observable} from "rxjs/index";
import {ApiResponse} from "../model/api.response";

@Injectable()
export class ApiService {

  constructor(private http: HttpClient) { }
  baseUrl: string = 'http://localhost:8080/users/';

  // ------------- request send in query params and form-data ---------------------------//
  // login(loginPayload) : Observable<ApiResponse> {
  //   return this.http.post<ApiResponse>('http://127.0.0.1:8000/api/' + 'token/generate-token?Secret=NDY4ZmJlM2YwZTgyNTgzMzgyYzViZWJlOTg3MTI1OWE5NTg4ODg0MA==&Public=456', loginPayload);
  // }
  // ------------- request send in query params and form-data ---------------------------//



  // ------------- request send in header data and form-data ---------------------------//
  login(loginPayload) : Observable<ApiResponse> {
    const httpOptions = {
      headers: new HttpHeaders({
        'Secret':  'NDY4ZmJlM2YwZTgyNTgzMzgyYzViZWJlOTg3MTI1OWE5NTg4ODg0MA==',
        'Public': '456'
      })
    };
    // const queryParams =  {
    //   params: new HttpParams().set('Secret','NDY4ZmJlM2YwZTgyNTgzMzgyYzViZWJlOTg3MTI1OWE5NTg4ODg0MA==')
    // }
    return this.http.post<ApiResponse>('http://127.0.0.1:8000/api/' + 'token/generate-token', loginPayload , httpOptions);
  }
  // ------------- request send in header data and form-data ---------------------------//


  signup(signupPayload): Observable<ApiResponse> {
    const httpOptions = {
      headers: new HttpHeaders({
        'Secret':  'NDY4ZmJlM2YwZTgyNTgzMzgyYzViZWJlOTg3MTI1OWE5NTg4ODg0MA==',
        'Public': '456'
      })
    };
    return this.http.post<ApiResponse>('http://127.0.0.1:8000/api/' + 'signup', signupPayload , httpOptions);
  }

  fetchlist() : Observable<ApiResponse> {
    return this.http.get<ApiResponse>('http://127.0.0.1:8000/api/' + 'fetch-list');
  }

  editlist(id: number): Observable<ApiResponse> {
    return this.http.get<ApiResponse>('http://127.0.0.1:8000/api/' + 'edit-list/' + id);
  }



  addlist(productPayload): Observable<ApiResponse>{
    console.log(productPayload);
    return this.http.post<ApiResponse>('http://127.0.0.1:8000/api/' + 'add-list', productPayload);
  }

  updatelist(productPayload,id: number): Observable<ApiResponse>{
    return this.http.post<ApiResponse>('http://127.0.0.1:8000/api/' + 'update-list/' + id, productPayload);
  }

  
  
  deletelist(id: number): Observable<ApiResponse>{
    return this.http.get<ApiResponse>('http://127.0.0.1:8000/api/' + 'delete-list/' + id);
  }
}
