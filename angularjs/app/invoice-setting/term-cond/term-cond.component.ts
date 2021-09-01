import { Component, OnInit,ViewChild } from '@angular/core';
import {MatTableDataSource} from '@angular/material/table';
import {MatPaginator} from '@angular/material/paginator'; 
import { MatSort } from '@angular/material/sort';
import {MatDialog, MatDialogRef, MAT_DIALOG_DATA} from '@angular/material/dialog';
import { HttpService } from 'src/app/http.service';
import { NgxSpinnerService } from "ngx-spinner";
import { ToastrService } from 'ngx-toastr';
@Component({
  selector: 'app-term-cond',
  templateUrl: './term-cond.component.html',
  styleUrls: ['./term-cond.component.css']
})
export class TermCondComponent implements OnInit {

  constructor(public http:HttpService,public dialog: MatDialog,private spinner: NgxSpinnerService,public toastr:ToastrService) { }

  listData:MatTableDataSource<any>; //LISTING DATA IN TABLE
  displayedColumns:string[]=['id','terms','action'];//FEILDS NAME SHOW IN TABLE

  @ViewChild(MatSort,null) sort:MatSort; //USE FOR FILTER
  @ViewChild(MatPaginator,null) paginator:MatPaginator; //USE FOR PAGINATION

  ngOnInit() {
   this.fetchterms();
  }

  //FUNCTION FOR APPLY FILTER TO LIST VALUE
  applyFilter(filtervalue:string){
    this.listData.filter=filtervalue.trim().toLocaleLowerCase();
    if (this.listData.paginator) {
      this.listData.paginator.firstPage();
    }
  }
  //fetch terma
  fetchterms()
    {
    this.spinner.show(); //show SPINNER
    this.http.get('invoice/fetchterm').subscribe((res:any)=>{ //API FOR SHOW ALL TERMS CONDITION OF COMPANY
    console.log(res);
    var recentvalue=res.result;
    this.listData=new MatTableDataSource(recentvalue); //SET VALUE INTO LIST FROM GET API
    console.log(this.listData)
    this.listData.sort=this.sort; //USE FOR FILTER VALUE 
    this.listData.paginator=this.paginator; //USE FOR PAGINATION 
    this.spinner.hide(); //HIDE SPINNER
    });
  }
  //UPDATE terms & condition
  update(id,term)
  {
    const data={
      term
    }
     console.log(data);
     this.spinner.show();//show the spinner
     this.http.put(`invoice/updateterm`,data,{id:id}).subscribe((res:any)=>{ //API FOR UPDATE TERMS & CONDN
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
  delete_term(id)
  {
    console.log(id);
    if(confirm("Are You Sure To Delete Data ?")){
        this.http.delete(`invoice/deleteterm/${id}`).subscribe((res: any) => { //API FOR DELETE terms & condition 
            console.log(res);
            if(res['message']=='Deleted successfully')
            {
                this.toastr.success('Data Deleted Successfully');
                this.ngOnInit()
            }
        });
    }
  }
  
}
