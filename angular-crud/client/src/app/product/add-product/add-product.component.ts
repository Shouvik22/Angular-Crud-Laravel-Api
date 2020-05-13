import { Component, OnInit } from '@angular/core';
import { NgForm } from '@angular/forms';
import { ApiService } from 'src/app/service/api.service';
import { Router } from '@angular/router';


@Component({
  selector: 'app-add-product',
  templateUrl: './add-product.component.html',
  styleUrls: ['./add-product.component.css']
})

export class AddProductComponent implements OnInit {
  selectedFile: File = null;

  constructor(private apiService: ApiService,private router: Router) { }

  ngOnInit() {
  }

  onFileSelected(event){
    this.selectedFile = <File>event.target.files[0];
  }
  
  onAddProduct(form: NgForm)
  {
    const value = form.value;
    
    // const productPayload = {
      
    //   name : value.name,
    //   price : value.price
    // }
    const productPayload = new FormData();
    productPayload.append('name',value.name);

    productPayload.append('price',value.price);
    productPayload.append('image',this.selectedFile,this.selectedFile.name);
    this.apiService.addlist(productPayload).subscribe(data =>{
      if(data.status == 200)
      {

        this.router.navigate(['/fetch-list']);
      }
    });
  }
}
