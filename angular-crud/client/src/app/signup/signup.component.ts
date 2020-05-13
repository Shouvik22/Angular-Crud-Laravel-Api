import { Component, OnInit } from '@angular/core';
import { NgForm } from '@angular/forms';
import { ApiService } from '../service/api.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-signup',
  templateUrl: './signup.component.html',
  styleUrls: ['./signup.component.css']
})


export class SignupComponent implements OnInit {

  constructor(public apiService: ApiService,public router:Router) { }

  ngOnInit() {
  }

  
  onSignUp(form: NgForm){
    if(form.value.password === form.value.repassword){
      
      const signupPayload = {
        username: form.value.name,
        password: form.value.password
      }

      this.apiService.signup(signupPayload).subscribe(data => {
        if(data.status == 200)
        {
          // window.localStorage.setItem("token",data.result.token);

          // window.localStorage.setItem("user_id",data.result.user_id);
          // window.localStorage.serItem("username",data.result.username);

          this.router.navigate(['/']);
        }
        else
        {
          alert('Invalid login!');
        }
      });
    }
    else{
      alert('Password does not match!');
    }
  }
}
