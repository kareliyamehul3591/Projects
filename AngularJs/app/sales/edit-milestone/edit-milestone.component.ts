import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators, FormArray } from '@angular/forms';
import { HttpService } from 'src/app/http.service';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from 'ngx-spinner';
import { MatTableDataSource } from '@angular/material/table';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-edit-milestone',
  templateUrl: './edit-milestone.component.html',
  styleUrls: ['./edit-milestone.component.css']
})
export class EditMilestoneComponent implements OnInit {
  //milestoneForm: FormGroup;

  listData:MatTableDataSource<any>; //LISTING DATA IN TABLE
  displayedColumns:string[]=['Milestone Name', 'Description', 'Send Alert', 'Action'];//FEILDS NAME SHOW IN TABLE
  order_type: any;
  recentdata: any;
  send_alert: any;
  
  constructor(private formBuilder: FormBuilder,public http:HttpService, private toastr: ToastrService,
    private spinner: NgxSpinnerService,private _route: ActivatedRoute) { }

  ngOnInit() {
    //get id by params
    this._route.params.subscribe(params => {
      let order_type = params['order_type'];
      this.order_type=order_type
      console.log(this.order_type);
      });
   // this.buildForm();
    this.fetchmilestone();
  }

  //get milestone details
  fetchmilestone()
  {
    console.log(this.order_type)
    this.spinner.show();//show the spinner
    this.http.get(`sales/viewmilestone`,{id:this.order_type}).subscribe((res:any)=>{ //api for show milestone details
      console.log(res);
      var milestonevalue=res.result;
      this.listData=new MatTableDataSource(milestonevalue); //SET VALUE INTO LIST FROM GET API
      console.log(this.listData)
     this.spinner.hide();//hide spinner
    })
  }

  //UPDATE milestone
  update(id,milestone_name,description,send_alert)
  {
    const data={
      milestone_name,description,
      send_alert:this.send_alert
    }
     console.log(data);
     this.spinner.show();//show the spinner
     this.http.put(`sales/editmilestone`,data,{id:id}).subscribe((res:any)=>{ //API FOR UPDATE milestone
     console.log(res);
     if(res['message']=='Updated successfully')
     {
      this.toastr.success('Data Updated Successfully');
      this.ngOnInit()
     }
      this.spinner.hide();//HIDDE the spinner
   })
  }

  //DELETE terms data aginst id
  delete_milestone(id)
  {
    console.log(id);
    if(confirm("Are You Sure To Delete Data ?")){
        this.http.delete(`sales/deletemilestone/${id}`).subscribe((res: any) => { //API FOR DELETE milestone
            console.log(res);
            if(res['message']=='Deleted successfully')
            {
                this.toastr.success('Data Deleted Successfully');
                this.ngOnInit()
            }
        });
    }
  }
  
  oncheckbox(values:any)
  {
    console.log(values.currentTarget.checked);
    this.send_alert=values.currentTarget.checked;

  }
  
}
