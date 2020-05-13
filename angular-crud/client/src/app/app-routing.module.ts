import { NgModule } from '@angular/core';
import { Routes,RouterModule } from '@angular/router';
import { LoginComponent } from './login/login.component';
import { ProductComponent } from './product/product.component';
import { AddProductComponent } from './product/add-product/add-product.component';
import { EditProductComponent } from './product/edit-product/edit-product.component';
import { SignupComponent } from './signup/signup.component';


const appRoute: Routes = [
    { path:  '', component: LoginComponent },
    
    { path: 'signup',component: SignupComponent },
    { path: 'fetch-list', component: ProductComponent },
    { path: 'fetch-add', component: AddProductComponent },
    { path: 'edit-list', component: EditProductComponent }
]

@NgModule({
    imports: [ RouterModule.forRoot(appRoute) ],
    exports: [ RouterModule ]
})
export class AppRoutingModue {}