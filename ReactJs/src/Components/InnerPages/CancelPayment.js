import React from 'react'
import './CancelPayment.css';
const CancelPayment = () => {
  return (
    <div><div class="container">
    <div class="row">
       <div class="col-md-6 mx-auto mt-5">
          <div class="payment">
             <div class="payment_header">
                <div class="check"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></div>
             </div>
             <div class="content">
                <h1>Opps ! Payment Faild</h1>
                <p>Something Went Wrong. </p>
                <a href="dashbord">Go to Dashboard</a>
             </div>
             
          </div>
       </div>
    </div>
 </div></div>
  )
}

export default CancelPayment