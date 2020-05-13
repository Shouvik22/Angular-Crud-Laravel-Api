import { Component, OnInit } from '@angular/core';
import { ApiResponse } from 'src/app/model/api.response';
import { ProductService } from 'src/app/service/product.service';
import { NgForm, Form } from '@angular/forms';
import { Router } from '@angular/router';
import { ApiService } from 'src/app/service/api.service';

@Component({
  selector: 'app-edit-product',
  templateUrl: './edit-product.component.html',
  styleUrls: ['./edit-product.component.css']
})

export class EditProductComponent implements OnInit {

  apiResponse: ApiResponse;

  constructor(private productService: ProductService,private router: Router,private apiService: ApiService) { }

  ngOnInit() {
    let productId = window.localStorage.getItem("editId");
    if(!productId) {
      alert("Invalid action.")

      this.router.navigate(['/fetch-list']);
      return;
    }
    // this.productService.activatedEmitter.subscribe(data =>{
    //   this.apiResponse = data.result;
    //   // console.log(data);
    // });
    this.apiService.editlist(+productId).subscribe(data =>{
      // console.log(data);
      this.apiResponse = data.result;
    });
  }

  

  
  onupdateProduct(form: NgForm)
  {
    
    let productId = window.localStorage.getItem("editId");
    const productPayload = {
      name : form.value.name,
      price : form.value.price
    }
    this.apiService.updatelist(productPayload,+productId).subscribe(data =>{
      if(data.status == 200)
      {
        this.router.navigate(['/fetch-list']);
      }
    });
  }

}
