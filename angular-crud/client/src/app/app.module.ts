import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpClientModule} from "@angular/common/http";

import { AppComponent } from './app.component';
import { LoginComponent } from './login/login.component';
import { AppRoutingModue } from './app-routing.module';
import { ApiService } from './service/api.service';
import { ProductComponent } from './product/product.component';
import { Product } from './model/prduct.model';
import { AddProductComponent } from './product/add-product/add-product.component';
import { EditProductComponent } from './product/edit-product/edit-product.component';
import { SignupComponent } from './signup/signup.component';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    ProductComponent,
    AddProductComponent,
    EditProductComponent,
    SignupComponent,
  ],
  imports: [
    BrowserModule,
    FormsModule,
    AppRoutingModue,
    HttpClientModule
  ],
  providers: [ApiService,Product],
  bootstrap: [AppComponent]
})
export class AppModule { }
