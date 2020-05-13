import { Component, OnInit } from '@angular/core';
import { ApiService } from '../service/api.service';
import { Product } from '../model/prduct.model';
import { Router } from '@angular/router';
import { ProductService } from '../service/product.service';


@Component({
  selector: 'app-product',
  templateUrl: './product.component.html',
  styleUrls: ['./product.component.css']
})

export class ProductComponent implements OnInit {
  public username: string;
  public results : Product[];
  public path: string = 'http://127.0.0.1:8000/uploads';
  constructor(private apiService: ApiService,
              private router: Router,
              private productService: ProductService) { }

  ngOnInit() {
    this.apiService.fetchlist().subscribe(data => {
      if(data.status == 200)
      {
        this.results = data.result;
        console.log(data);
      }
    });
  }

  onEdit(index: number){
    window.localStorage.setItem("editId", index.toString());
    
    this.username =  window.localStorage.getItem('username');
    this.apiService.editlist(+index).subscribe(data =>{
      // console.log(data);
      this.productService.activatedEmitter.emit(data);
    });

    this.router.navigate(['/edit-list']);
  }

  onDelete(result: Product){
    this.apiService.deletelist(+result.id).subscribe(data => {
      this.results = this.results.filter(u => u !== result);
    });
  }



  onAdd(){
    this.router.navigate(['/fetch-add']);
  }


  logout(){
    localStorage.clear();
    this.router.navigate(['/']);
  }

}
