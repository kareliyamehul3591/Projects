<script>
/**
 * Transactions component
 */
export default {
  props: {
    transactions: {
      type: Array,
      default() {
        return [];
      },
    },
  },
  data() {
    return {
      showModal: false,
    };
  },
};
</script>

<template>
  <div class="table-responsive mb-0">
    <table class="table table-centered table-nowrap">
      <thead class="thead-light">
        <tr>
          <th style="width: 20px;">
            <div class="custom-control custom-checkbox">
              <input id="customCheck" type="checkbox" class="custom-control-input" />
              <label class="custom-control-label" for="customCheck">&nbsp;</label>
            </div>
          </th>
          <th>{{translate("order-id","Order ID")}}</th>
          <th>{{translate("billing-name","Billing Name")}}</th>
          <th>{{translate("date","Date")}}</th>
          <th>{{translate("total","Total")}}</th>
          <th>{{translate("payment-status","Payment Status")}}</th>
          <th>{{translate("payment-method","Payment Method")}}</th>
          <th>{{translate("view-details","View Details")}}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="data in transactions" :key="data.id">
          <td>
            <div class="custom-control custom-checkbox">
              <input :id="`customCheck${data.index}`" type="checkbox" class="custom-control-input" />
              <label class="custom-control-label" :for="`customCheck${data.index}`">&nbsp;</label>
            </div>
          </td>
          <td>
            <a href="javascript: void(0);" class="text-body font-weight-bold">{{data.id}}</a>
          </td>
          <td>{{data.name}}</td>
          <td>{{data.date}}</td>
          <td>{{data.total}}</td>
          <td>
            <span
              class="badge badge-pill badge-soft-success font-size-12"
              :class=" { 'badge-soft-danger': `${data.status}` === 'Chargeback',
                        'badge-soft-warning': `${data.status}` === 'Refund' }"
            >{{data.status}}</span>
          </td>
          <td>
            <i :class="`fab ${data.payment[0]} mr-1`"></i>
            {{data.payment[1]}}
          </td>
          <td>
            <!-- Button trigger modal -->
            <button
              type="button"
              class="btn btn-primary btn-sm btn-rounded"
              @click="showModal = true"
            >{{translate("view-details","View Details")}}</button>
          </td>
        </tr>
      </tbody>
    </table>
    <b-modal v-model="showModal" title="Order Details" centered>
      <p class="mb-2">
        {{translate("product-id","Product id")}}:
        <span class="text-primary">#SK2540</span>
      </p>
      <p class="mb-4">
        {{translate("billing-name","Billing Name")}}:
        <span class="text-primary">{{translate("neal-matthews","Neal Matthews")}}</span>
      </p>
      <div class="table-responsive">
        <table class="table table-centered table-nowrap">
          <thead>
            <tr>
              <th scope="col">{{translate("product","Product")}}</th>
              <th scope="col">{{translate("product-name","Product Name")}}</th>
              <th scope="col">{{translate("price","Price")}}</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">
                <div>
                  <img src="@/assets/images/product/img-7.png" alt class="avatar-sm" />
                </div>
              </th>
              <td>
                <div>
                  <h5 class="text-truncate font-size-14">{{translate("wireless-headphone-(black)","Wireless Headphone (Black)")}}</h5>
                  <p class="text-muted mb-0">$ 225 x 1</p>
                </div>
              </td>
              <td>$ 255</td>
            </tr>
            <tr>
              <th scope="row">
                <div>
                  <img src="@/assets/images/product/img-4.png" alt class="avatar-sm" />
                </div>
              </th>
              <td>
                <div>
                  <h5 class="text-truncate font-size-14">{{translate("phone-patterned-cases","Phone patterned cases")}}</h5>
                  <p class="text-muted mb-0">$ 145 x 1</p>
                </div>
              </td>
              <td>$ 145</td>
            </tr>
            <tr>
              <td colspan="2">
                <h6 class="m-0 text-right">{{translate("sub-total","Sub Total")}}:</h6>
              </td>
              <td>$ 400</td>
            </tr>
            <tr>
              <td colspan="2">
                <h6 class="m-0 text-right">{{translate("shipping","Shipping")}}:</h6>
              </td>
              <td>{{translate("free","Free")}}</td>
            </tr>
            <tr>
              <td colspan="2">
                <h6 class="m-0 text-right">{{translate("total","Total")}}:</h6>
              </td>
              <td>$ 400</td>
            </tr>
          </tbody>
        </table>
      </div>
      <template v-slot:modal-footer>
        <b-button variant="secondary" @click="showModal = false">{{translate("close","Close")}}</b-button>
      </template>
    </b-modal>
  </div>
  <!-- end table -->
</template>
