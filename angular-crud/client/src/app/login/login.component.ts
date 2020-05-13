import { Component, OnInit } from '@angular/core';
import { NgForm } from '@angular/forms';
import { ApiService } from '../service/api.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})


export class LoginComponent implements OnInit {

  
  constructor(private apiService: ApiService,private router: Router) { }

  ngOnInit() {
  }

  onAddItem(form: NgForm)
  {
    const value = form.value;
    const loginPayload = {
      username: value.name,
      password: value.password
    }
    // console.log(value.name);
    this.apiService.login(loginPayload).subscribe(data =>{
      if(data.status == 200)
      {
        window.localStorage.setItem("token",data.result.token);

        window.localStorage.setItem("user_id",data.result.user_id);        
        this.router.navigate(['/fetch-list']);
      }
      else
      {
        alert('Invalid login!');
      }
    });
  }
}
