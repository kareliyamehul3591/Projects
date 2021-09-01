import { Component, OnInit, ViewChild } from '@angular/core';
import { HttpService } from 'src/app/http.service';
import { NgxSpinnerService } from 'ngx-spinner';
import { ToastrService } from 'ngx-toastr';
import { MatDialog } from '@angular/material/dialog';
import { MatTableDataSource } from '@angular/material/table';
import { MatSort } from '@angular/material/sort';
import { MatPaginator } from '@angular/material/paginator';
import { EditMilestoneComponent } from '../edit-milestone/edit-milestone.component';
import { Router } from '@angular/router';
@Component({
  selector: 'app-list-milestones',
  templateUrl: './list-milestones.component.html',
  styleUrls: ['./list-milestones.component.css']
})
export class ListMilestonesComponent implements OnInit {

  constructor(public router:Router,public http:HttpService,private spinner: NgxSpinnerService,private toastr: ToastrService,public dialog: MatDialog) { }

  listData:MatTableDataSource<any>;//LISTING DATA IN TABLE
  displayedColumns:string[]=['Order Type','Action'];  //FEILDS NAME SHOW IN TABLE

  @ViewChild(MatSort,null) sort:MatSort; //USE FOR FILTER
  @ViewChild(MatPaginator,null) paginator:MatPaginator; //USE FOR PAGINATION

  ngOnInit() {
    this.fetchmilestone();
  }

  //get milestone value
  fetchmilestone()
  {
    this.http.get('sales/getmilestone').subscribe((res:any)=>{ //api for show milestone details
      console.log(res);
      var recentvalue=res.result;
      this.listData=new MatTableDataSource(recentvalue); //SET VALUE INTO LIST FROM GET API
      console.log(this.listData)
      this.listData.sort=this.sort; //USE FOR FILTER VALUE 
      this.listData.paginator=this.paginator; //USE FOR PAGINATION
    })
  }

    //FUNCTION FOR APPLY FILTER TO LIST VALUE
    applyFilter(filtervalue:string){
      this.listData.filter=filtervalue.trim().toLocaleLowerCase();
      if (this.listData.paginator) {
        this.listData.paginator.firstPage();
      }
    }
  

  status(event) {
    
  }

  //delete milestone details
  delete_milestone(order_type)
  {
    if(confirm("Are You Sure To Delete Data ?")){
      this.spinner.show();//show the spinner
        this.http.delete(`sales/milestone_delete_ordertype/${order_type}`).subscribe((res: any) => { //api for delete milestone detail
        console.log(res);
        if(res['message']=='Deleted successfully'){
        this.toastr.success('Data Deleted Successfully');
        this.ngOnInit()
        }
        this.spinner.hide();
        }, err=>{
          this.toastr.error(err.message || err);
        }
        );
    }
  }

  //update milestone modal
  edit_milestone(order_type) {
    console.log(order_type);
    this.router.navigate([`/edit-milestone-info/${order_type}`])
  }


}
