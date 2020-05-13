import { Injectable, EventEmitter } from '@angular/core';
import {ApiResponse} from "../model/api.response";

@Injectable({
  providedIn: 'root'
})

export class ProductService {
  
  activatedEmitter = new EventEmitter<ApiResponse>();
  constructor() { }
}
