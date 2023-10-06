  <template>
    
    <Table>
      <table-head>
        <table-head-cell>PIV name</table-head-cell>
        <table-head-cell v-if="databaseid != 3">Amount</table-head-cell>
        <table-head-cell v-if="databaseid != 3">Barcode</table-head-cell>
        <table-head-cell v-if="databaseid == 3">Targa</table-head-cell>
        <table-head-cell v-if="databaseid == 3">Who is User</table-head-cell>
        <table-head-cell>Comments</table-head-cell>
        <table-head-cell>Date of Input</table-head-cell>
        <table-head-cell>Date of Modify</table-head-cell>
        <table-head-cell>Name of Input</table-head-cell>
        <table-head-cell>Name of Modify</table-head-cell>
        <table-head-cell v-if="!status"></table-head-cell>
      </table-head>
      <table-body>
        <table-row v-for=" datum in data ">
          <table-cell>{{ datum['name'] }}</table-cell>
          <table-cell v-if="databaseid != 3">{{ datum['amount'] }}</table-cell>
          <table-cell v-if="databaseid != 3">{{ datum['barcode'] }}</table-cell>
          <table-cell v-if="databaseid == 3">{{ datum['targa'] }}</table-cell>
          <table-cell v-if="databaseid == 3">{{ datum['username'] }}</table-cell>
          <table-cell>{{ datum['comments'] }}</table-cell>
          <table-cell>{{ convertDate(datum['doi'].split(' ')[0])+' '+ datum['doi'].split(' ')[1] }}</table-cell>
          <table-cell>{{ convertDate(datum['dom'].split(' ')[0])+' '+ datum['dom'].split(' ')[1] }}</table-cell>
          <table-cell>{{ datum['mne'] }}</table-cell>
          <table-cell>{{ datum['mnm'] }}</table-cell>
          

          <table-cell v-if="databaseid != 3 && !status && (datum['amount']-datum['usedamount']>0)">
            <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline" @click="orderClicked(datum)">Order</a>
          </table-cell>
          <table-cell v-if="databaseid == 3 && !status && !datum['used']">
            <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline" @click="orderClicked(datum)">Order</a>
          </table-cell>
        </table-row>
      </table-body>
    </Table>

    <Modal :show="isModalVisible" @close="closeModal">
      <template #content>
          <!-- Container for modal content -->
          <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all" :class="maxWidthClass">

              <!-- Modal Header -->
              <div class="p-1 text-center border-b border-gray-200">
                  <h2 class="text-2xl font-semibold text-gray-800" style="font-size: 1rem; margin-left: 10px; margin-right: 10px">Request for PIV</h2>
              </div>

              <!-- Modal Body -->
              <div class="p-2 text-center">
                <table class="w-full">
                  <thead>
                    <tr>
                      <th>Type</th>
                      <th>Name</th>
                      <th>Amount</th>
                      <!-- Add more table headers as needed -->
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Use v-for to iterate through your data and populate the rows -->
                    <tr>
                      <td>{{ databases[databaseid-1] }}</td>
                      <td>{{ curdata.name }}</td>
                      <td>{{ curdata.amount }}</td>
                      <!-- Add more table cells to display data as needed -->
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- DATE LINE -->
              <div id="fromto" style="margin: 1rem;" class="flex  flex-row gap-4" >

                  <div class="basis-1/2">
                    <p class="ml-2" style="color: gray">Selete Date</p>
                    <flat-pickr 
                                id="fromthe"
                                :config = "config_date"
                                v-model="s_date"
                                placeholder="DD-MM-YY"
                                class=" block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                readonly="readonly"
                    />
                      
                  </div>
                  
                  <div class="basis-1/2">
                    <p class="ml-2" style="color: gray">Input Quantity</p>
                    <NumberInput v-if="databaseid==3" v-model="quantity" :max="1" id="quantity"/>
                    <NumberInput v-else v-model="quantity" :max="curdata.amount-curdata.usedamount" id="quantity"/>
                  </div>

              </div>
              <!-- END -->

              <!-- Modal Footer -->
              <div class="p-2 flex justify-center" style="
                      padding-top: 0px;
                      padding-bottom: 16px" >
                  <PrimaryButton class="mt-4 justify-end" style="margin-right: 10px;" name="Close" @click="closeModal">Close</PrimaryButton>
                  <PrimaryButton class="mt-4 justify-end" style="background-color: lightcoral;" name="Send" @click="requestVIP">Send</PrimaryButton>
              </div>
          </div>
      </template>
    </Modal>
  </template>
  
  <script setup>
  import { Table, TableHead, TableBody, TableHeadCell, TableRow, TableCell } from 'flowbite-vue'
  import Modal from './Modal.vue'
  import NumberInput from './NumberInput.vue';
  import PrimaryButton from './PrimaryButton.vue'
  import flatPickr from 'vue-flatpickr-component';
  import {Italian} from 'flatpickr/dist/l10n/it.js';
  import { ref } from 'vue'

  const emit = defineEmits();
  const {data, databaseid, status, usr} = defineProps(['data', 'databaseid', 'status', 'usr']);
  const isModalVisible = ref(false);
  const curdata = ref(data[0]);
  const s_date = ref();
  const quantity = ref(1);
  const databases = ['Products', 'Instruments', 'Vehciles'];
  
  const config_date = ref({
  // set wrap to true only when using 'input-group'
      altFormat: 'd-m-Y',
      dateFormat: 'd-m-Y',
      locale: Italian, // locale for this instance only

      "disable": [
          function (date) {
              // return true to disable
              return (date.getDay() === 0 || date.getDay() === 6);

          },
          {
              from:"01-01-1901",
              to: new Date().fp_incr(-1),
          }

      ]
  });

  const orderClicked = (datum) => {
    // Call your 'orderClicked' function with the ID
    // You can perform any actions you need here
    curdata.value = datum;
    showModal();
  };

  function closeModal(){
    isModalVisible.value = false;
    s_date.value = '';
    quantity.value = 1;
  }

  function requestVIP(){
    if( s_date.value == null || s_date.value == '')
    {
      closeModal();
      emit('on-sent', false)
      return;
    }
    registerVIPToWarehouse(createJSON());
    closeModal();
  }
  
  
  async function registerVIPToWarehouse( objdata ) {
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    try {
        const response = await axios.post('/registerPIVToWarehouse', {
            data: {
                objdata,
            }
        },{
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
        });
        
        // Handle the response
        emit('on-sent', true)
        
    } catch (error) {
        // Handle errors
        console.log(error);
    }
  }


  function createJSON(){
    
    const jsonObject = {};
    jsonObject.PIVid = curdata.value.id;
    jsonObject.PIVname = curdata.value.name;
    jsonObject.employee = usr;
    jsonObject.PIVtype = databaseid;
    if( databaseid != 3 )
      jsonObject.quantity = quantity.value;
    jsonObject.request_date = convertDate(s_date.value);

    return jsonObject;
  }

  function convertDate( italianDate ) {
      const parts = italianDate.split('-');
      const [day, month, year] = parts;
      const ukDate = `${year}-${month}-${day}`;
      return ukDate;
  }

  function showModal(){
    isModalVisible.value = true;
  }
  </script>

  <style scoped>
  .info-table {
    color: gray;
    margin-left: 20px; /* Add margin to align with other content */
  }

  .info-row {
    margin-bottom: 10px; /* Add margin between rows */
  }

  .info-label {
    font-weight: bold;
  }

  .info-value {
    word-break: break-word; /* Allow long values to break to the next line */
  }
  </style>