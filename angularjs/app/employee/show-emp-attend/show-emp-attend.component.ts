import { Component, OnInit, ViewChild } from "@angular/core";
import { HttpService } from "src/app/http.service";
import { formatDate } from "@angular/common";
import { ToastrService } from "ngx-toastr";
import {
    MatDialog,
    MatDialogRef,
    MAT_DIALOG_DATA,
} from "@angular/material/dialog";
import { MatTableDataSource } from "@angular/material/table";
import { MatPaginator } from "@angular/material/paginator";
import { MatSort } from "@angular/material/sort";
import { EditattendComponent } from "../editattend/editattend.component";
import { NgxSpinnerService } from "ngx-spinner";
import { ngxCsv } from "ngx-csv/ngx-csv";

import { DateRangePicker } from '@syncfusion/ej2-calendars';



@Component({
    selector: "app-show-emp-attend",
    templateUrl: "./show-emp-attend.component.html",
    styleUrls: ["./show-emp-attend.component.css"],
})

export class ShowEmpAttendComponent implements OnInit {
    recentvalue: any; //DECLARE
    date = ""; //DECLARE

    constructor(
        public http: HttpService,
        public dialog: MatDialog,
        public toastr: ToastrService,
        private spinner: NgxSpinnerService
    ) { }

    listData: MatTableDataSource<any>; //LISTING DATA IN TABLE
    displayedColumns: string[] = [
        "emp_code",
        "fullname",
        "date",
        "check_in",
        "check_out",
        "worked_hours",
        "attendance",
        "activity",
        "id",
    ]; //FEILDS NAME SHOW IN TABLE

    @ViewChild(MatSort, null) sort: MatSort; //USE FOR FILTER
    @ViewChild(MatPaginator, null) paginator: MatPaginator; //USE FOR PAGINATION

    ngOnInit() {
        this.spinner.show(); //show the spinner
        this.http.get("leave/showAllattend").subscribe((res: any) => {
            //API FOR SHOW ALL EMPLOYEE ATTENDANCE
            console.log(res);
            this.data = res.result;
            var recentvalue = res.result;
            //   console.log(recentvalue)
            this.listData = new MatTableDataSource(recentvalue); //SET VALUE INTO LIST FROM GET API
            console.log(this.listData);
            this.listData.sort = this.sort; //USE FOR FILTER VALUE
            this.listData.paginator = this.paginator; //USE FOR PAGINATION
            this.spinner.hide(); //HIDE SPINNER
            console.log(this.filedownload);
        });
    }

    data = [
        {
            emp_code: "RAK-2021-160",
            fullname: "Sandeep Kolhe",
            emp_id: 160,
            date: "2021-02-05",
            check_in: "2021-02-05T01:03:30.000Z",
            id: 112,
            check_out: null,
            attendance: "P",
            worked_hours: "0",
            activity: null,
        },
    ];

    filedownload() {
        var options = {
            fieldSeparator: ",",
            quoteStrings: '"',
            decimalseparator: ".",
            showLabels: true,
            showTitle: true,
            title: "Employees Attendance List",
            useBom: true,
            headers: [
                "emp_code",
                "fullname",
                "date",
                "check_in",
                "check_out",
                "attendance",
                "worked_hours",
                "activity",
                "Employee_id",
                "id"
            ],
        };
        new ngxCsv(this.data, "filename", options);
    }

    //FUNCTION FOR APPLY FILTER TO LIST VALUE
    applyFilter(filtervalue: string) {
        this.listData.filter = filtervalue.trim().toLocaleLowerCase();
        if (this.listData.paginator) {
            this.listData.paginator.firstPage();
        }
    }

    

    //UPDATE MODAL FORM
    update(id) {
        console.log(id);
        localStorage.setItem("id", id);
        const dialogRef = this.dialog.open(EditattendComponent, {
            width: "50%",
            height: "80%",
        });
        dialogRef.afterClosed().subscribe((result) => {
            console.log(`Dialog result: ${result}`);
            this.ngOnInit();
        });
    }

    //DELETE MODAL FORM
    delete_emp(id) {
        console.log(id);
        if (confirm("Are You Sure To Delete Data ?")) {
            this.http.delete(`leave/${id}`).subscribe((res: any) => {
                //API FOR DELETE EMPLOYEE ATTENDANCE
                console.log(res);
                this.toastr.success("Data Deleted Successfully");
                this.ngOnInit();
            });
        }
    }
}



