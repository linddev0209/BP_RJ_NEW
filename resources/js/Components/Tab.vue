<template>
    <div class="w-full sm:px-0">
      <TabGroup>
        <TabList class="flex space-x-1 rounded-xl bg-blue-900/20 p-1">
          <Tab
            v-for="category in Object.keys(categories)"
            as="template"
            :key="category"
            v-slot="{ selected }"
          >
            <button
              :class="[
                'w-full rounded-lg py-2.5 text-sm font-medium leading-5 text-blue-700',
                'ring-white ring-opacity-60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
                selected
                  ? 'bg-white shadow'
                  : 'text-blue-100 hover:bg-white/[0.12] hover:text-white',
              ]"
            >
              {{ category.replace(/_/g, "\n") }}
            </button>
          </Tab>
        </TabList>
  
        <TabPanels class="mt-2">
          <TabPanel
            v-for="(posts, idx) in Object.values(categories)"
            :key="idx"
            :class="[
              'rounded-xl bg-white p-3',
              'ring-white ring-opacity-60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
            ]"
          >
            <div v-if="idx == 0 " class="container">
              
                <div id="chkbox" style="margin-top: 1rem;" class="flex  flex-row gap-4" >
                  <div class="basis-1/2">
                  </div>
                  <div class="basis-1/2">
                  </div>
                  <div class="basis-1/2">
                    <Combobox :people = "combodata" :selidx = "pivindexTable-1" @on-change="ComboTableClicked"/>
                  </div>
                </div>
                <br />
                <div class="table-container" v-if="pivindexTable == 1 && products.length > 0"><Table :data = "products" :databaseid = "pivindexTable" :status = "mflag" :usr = "who" @on-sent="validateReqDate"></Table></div>
                <div class="table-container" v-else-if="pivindexTable == 2 && instruments.length > 0"><Table :data = "instruments" :databaseid = "pivindexTable" :status = "mflag" :usr = "who" @on-sent="validateReqDate"></Table></div>
                <div class="table-container" v-else-if="pivindexTable == 3 && vehciles.length > 0"><Table :data = "vehciles" :databaseid = "pivindexTable" :status = "mflag" :usr = "who" @on-sent="validateReqDate"></Table></div>
              
            </div>
            <div v-if="idx==1" class="container">
              <div v-if="mflag" class="row">
                <Disclosure v-for="post in posts" v-slot="{ open }" as="div" class="mt-2">
                  <DisclosureButton
                  class="flex w-full justify-between rounded-lg bg-purple-100 px-4 py-2 text-left text-sm font-medium text-purple-900 hover:bg-purple-200 focus:outline-none focus-visible:ring focus-visible:ring-purple-500 focus-visible:ring-opacity-75"
                  >
                      <span>{{ post.value.title }}({{ post.value.date }})</span>
                      <ChevronUpIcon
                          :class="open ? 'rotate-180 transform' : ''"
                          class="h-5 w-5 text-purple-500"
                      />  
                  </DisclosureButton>
                  <DisclosurePanel class="px-4 pt-4 pb-2 text-sm text-gray-500">
                    <span class="mt-4 text-1xl font-bold text-yellow-700">
                      <h1 style="color: #007bff">{{ dropdownOptions[post.value.databaseid-1] }}</h1>
                      <ul class="info-list">
                        <li class="info-list-item">
                          {{ post.value.name }}
                          
                          <div class="row">
                            <button v-if="post.value.quantity - post.value.allowedNum > 0" style="vertical-align: middle; margin-right: 10px;" data-toggle="tooltip" title="Waiting...">
                              <span  class="badge">{{ post.value.quantity - post.value.allowedNum }}</span>
                              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                              </svg>
                            </button>
                            <button v-if="post.value.allowedNum > 0" style="vertical-align: middle; margin-right: 10px;" data-toggle="tooltip" title="Allowed">
                              <span class="badge">{{ post.value.allowedNum - post.value.returnedNum }}</span>
                              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-cart-check-fill" viewBox="0 0 16 16">
                                <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-1.646-7.646-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L8 8.293l2.646-2.647a.5.5 0 0 1 .708.708z"/>
                              </svg>
                            </button>
                            <button v-if="post.value.quantity - post.value.allowedNum > 0" class="right-button" @click="allowAction(post)">Allow</button>
                            <button v-else class="right-button" @click="allowAction(post)" style="background-color: lightgray;" disabled>Allow</button>
                            <button v-if="post.value.databaseid != 1" class="right-button-red" @click="returnAction(post)">Returned</button>
                          </div>
                        </li>
                      </ul><br/>
                    </span><br/>
                  </DisclosurePanel>
                </Disclosure>
                <!-- <div class="row">
                  <Pagination />
                </div> -->
              </div>
              <div v-else class="row">
                <Disclosure v-for="post in posts" v-slot="{ open }" as="div" class="mt-2">
                  <DisclosureButton v-if=" post.value.title == who "
                  class="flex w-full justify-between rounded-lg bg-purple-100 px-4 py-2 text-left text-sm font-medium text-purple-900 hover:bg-purple-200 focus:outline-none focus-visible:ring focus-visible:ring-purple-500 focus-visible:ring-opacity-75"
                  >
                      <span>{{ post.value.date }}</span>
                      <ChevronUpIcon
                          :class="open ? 'rotate-180 transform' : ''"
                          class="h-5 w-5 text-purple-500"
                      />  
                  </DisclosureButton>
                  <DisclosurePanel v-if=" post.value.title == who " class="px-4 pt-4 pb-2 text-sm text-gray-500">
                    <span class="mt-4 text-1xl font-bold text-yellow-700">
                      <h1 style="color: #007bff">{{ dropdownOptions[post.value.databaseid-1] }}</h1>
                      <ul class="info-list">
                        <li class="info-list-item">
                          {{ post.value.name }}
                          
                          <div class="row">
                            <button v-if="post.value.quantity - post.value.allowedNum > 0" style="vertical-align: middle; margin-right: 10px;" data-toggle="tooltip" title="Waiting...">
                              <span  class="badge">{{ post.value.quantity - post.value.allowedNum }}</span>
                              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                              </svg>
                            </button>
                            <button v-if="post.value.allowedNum > 0" style="vertical-align: middle; margin-right: 10px;" data-toggle="tooltip" title="Allowed">
                              <span class="badge">{{ post.value.allowedNum - post.value.returnedNum }}</span>
                              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-cart-check-fill" viewBox="0 0 16 16">
                                <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-1.646-7.646-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L8 8.293l2.646-2.647a.5.5 0 0 1 .708.708z"/>
                              </svg>
                            </button>
                          </div>
                        </li>
                      </ul><br/>
                    </span><br/>
                  </DisclosurePanel>
                </Disclosure>
              </div>
            </div>
            <div v-else-if="idx==2" class="container">
              <form id="register_form" @submit="onRegisterBtnClicked">
                <div id="chkbox" style="margin-top: 1rem;" class="flex  flex-row gap-4" >
                  <div class="basis-1/2">
                    <p class="ml-2" style="color: gray">Select PIV Type</p>
                    <Combobox id="pivtype_reg" :people = "combodata" :selidx = "pivindex-1" @on-change="ComboClicked" />
                  </div>
                  <div v-if="pivindex!=3" class="basis-1/2">
                    <p class="ml-2" style="color: gray">Input Barcode</p>
                    <TextInput id="barcode_reg" v-model="barcode_reg" />
                  </div>
                  <div v-else class="basis-1/2">
                    <p class="ml-2" style="color: gray">Who is using it</p>
                    <TextInput id="owner_reg" v-model="owner_reg" />
                  </div>
                </div>
                <div id="selectForm">
                  <!-- CHECK BOX LINE -->
                  <div id="chkbox" style="margin-top: 1rem;" class="flex  flex-row gap-4" >
                    <div class="basis-1/2">
                      <p class="ml-2" style="color: gray">Input Name</p>
                      <TextInput id="name_reg" v-model="name_reg" />
                    </div>
                    <div v-if="pivindex!=3 " class="basis-1/2">
                      <p class="ml-2" style="color: gray">Input Quantity</p>
                      <NumberInput v-model="quantity_reg" id="quantity_reg" />
                    </div>
                    <div v-else class="basis-1/2">
                      <p class="ml-2" style="color: gray">Input Targa</p>
                      <TextInput id="targa_reg" v-model="targa_reg" />
                    </div>
                  </div>
                  <!-- END -->

                  <!-- TEXTAREA LINE -->
                  <textarea
                    v-model="comment_reg"
                    id="comments_reg"
                    placeholder="Comments"
                    class="mt-4 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                  ></textarea>
                  <!-- END -->

                  <!-- SUBMIT BUTTON -->
                  <PrimaryButton class="mt-4 justify-end">Register</PrimaryButton>
                  <!-- END -->
                </div>
              </form>
            </div>
            <div v-else-if="idx==3" class="container">
              <form id="update_form" @submit="onUpdateBtnClicked">
                <div id="chkbox" style="margin-top: 1rem;" class="flex  flex-row gap-4" >
                  <div class="basis-1/2">
                    <p class="ml-2" style="color: gray">Select PIV Type</p>
                    <Combobox id="pivtype_upt" :people = "combodata" :selidx = "pivindex1-1" @on-change="updatecomboclicked" />
                  </div>
                  <div class="basis-1/2">
                    <p class="ml-2" style="color: gray">Select PIV Name</p>
                    <Combobox v-if="pivindex1 == 1" id="pivname_upt" :people = "products" :selidx = "pivindex2-1" @on-change="namecomboclicked" />
                    <Combobox v-else-if="pivindex1 == 2" id="pivname_upt" :people = "instruments" :selidx = "pivindex2-1" @on-change="namecomboclicked" />
                    <Combobox v-else-if="pivindex1 == 3" id="pivname_upt" :people = "vehciles" :selidx = "pivindex2-1" @on-change="namecomboclicked" />
                    <Combobox v-else id="pivname_upt" :people = "[]" :selidx = "pivindex2-1" @on-change="namecomboclicked" />
                  </div>
                  <div v-if="pivindex1 !=3 " class="basis-1/2">
                    <p class="ml-2" style="color: gray">Input Barcode</p>
                    <TextInput id="barcode_upt" v-model="barcode_upt" />
                  </div>
                  <div v-else class="basis-1/2">
                    <p class="ml-2" style="color: gray">Who is using it</p>
                    <TextInput id="owner_upt" v-model="owner_upt" />
                  </div>
                </div>
                <div id="selectForm">
                  <!-- CHECK BOX LINE -->
                  <div id="chkbox" style="margin-top: 1rem;" class="flex  flex-row gap-4" >
                    <div class="basis-1/2">
                      <p class="ml-2" style="color: gray">Input Name</p>
                      <TextInput id="name_upt" v-model="name_upt" />
                    </div>
                    <div v-if="pivindex1 !=3 " class="basis-1/2">
                      <p class="ml-2" style="color: gray">Input Quantity</p>
                      <NumberInput v-model="quantity_upt" id="quantity_upt"/>
                    </div>
                    <div v-else class="basis-1/2">
                      <p class="ml-2" style="color: gray">Input Targa</p>
                      <TextInput id="targa_upt" v-model="targa_upt" />
                    </div>
                  </div>
                  <!-- END -->

                  <!-- TEXTAREA LINE -->
                  <textarea
                    v-model="comment_upt"
                    id="comments_upt"
                    placeholder="Comments"
                    class="mt-4 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                  ></textarea>
                  <!-- END -->

                  <!-- SUBMIT BUTTON -->
                  <PrimaryButton class="mt-4 justify-end" style="margin-right: 10px;" name="update">Update</PrimaryButton>
                  <PrimaryButton class="mt-4 justify-end" style="background-color: lightcoral;" name="delete">Delete</PrimaryButton>
                  <!-- END -->
                </div>
              </form>
            </div>
          </TabPanel>
        </TabPanels>
      </TabGroup>
    </div>
  </template>
  
  <script setup>
  import { inject, ref } from 'vue'
  import { TabGroup, TabList, Tab, TabPanels, TabPanel, Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
  import Pagination from './Pagination.vue';
  import Dropdown from './Dropdown.vue';
  import DropdownOption from './DropdownOption.vue';
  import PrimaryButton from './PrimaryButton.vue';
  import Combobox from './ComboBox.vue';
  import NumberInput from './NumberInput.vue';
  import TextInput from './TextInput.vue';
  import Table from './Table.vue'
  import VueLatestTable from './VueLatestTable.vue'
  import $ from 'jquery';

  const emit = defineEmits();
  const dropdownOptions = ["Products", "Instruments", "Vechiles"];
  const {categories, mflag, who, combodata} = defineProps(['categories', 'mflag', 'who', 'combodata']);
  const products = inject('products')
  const instruments = inject('instruments')
  const vehciles = inject('vehciles')

  // Tab Control
  const selectedTab = ref(2);

  // PIV Type Selection
  const pivindexTable = ref(1);
  const pivindex = ref(0);
  const pivindex1 = ref(0);
  const pivindex2 = ref(0);
  //
  
  // Register V-Model Values
  const quantity_reg = ref(1);
  const barcode_reg = ref('');
  const name_reg = ref('');
  const comment_reg = ref('');
  const targa_reg = ref('');
  const owner_reg = ref('');
  //

  // Update V-Model Values
  const piv_id = ref(0);
  const quantity_upt = ref(1);
  const barcode_upt = ref('');
  const name_upt = ref('');
  const comment_upt = ref('');
  const targa_upt = ref('');
  const owner_upt = ref('');
  //

  const isDisabled = ref(false);

  const allowAction = (post) => {
    
    if( post.value.allowedNum < post.value.quantity )
    {
      post.value.allowedNum += 1;
      updateWarehouseAllowed(createJSONEdit(post, true), post);
    }
  }

  const returnAction = (post) => {
    if( ( post.value.returnedNum < post.value.quantity ) && ( post.value.allowedNum == post.value.quantity ) )
    {
      post.value.returnedNum += 1;
      updateWarehouseReturned(createJSONEdit(post, false), post);    
    }
  }


  const onRegisterBtnClicked = (event) => {
    event.preventDefault();

    validateInput( true )

    // AJAX Post for Registration
    registerWarehouse(createJSON(pivindex.value))
    //
  }

  const onUpdateBtnClicked = (event) => {
    event.preventDefault()
    validateInput( false )

    // AJAX Post for Registration
    if( event.submitter.name == 'update' )
      updateWarehouse(createJSONUPT(pivindex1.value))
    else
      deleteWarehouse()
    //
  }

  const validateReqDate = (flag) => {
    
    if( flag )
      emit('on-load', 'Request Successfully Sent!', 2);
    else
      emit('on-load', 'Request Date Field Should Not Be Empty!', 1);
  }

  function validateInput( flag ){
    if( flag )
    {
      // Exception Handle
      if( pivindex.value == 0 )
      {
        emit('on-load', 'Please Select the PIV Type!', 1);
        return false;
      }
      else
      {
        if( name_reg.value == '' )
        {
            emit('on-load', 'Name Field Should Not Be Empty!', 1);
            return false;
        }

        if( pivindex.value == 3 )
        {
          if( targa_reg.value == '' )
          {
            emit('on-load', 'Targa Field Should Not Be Empty!', 1);
            return false;
          }
        }
      }
      //
    }
    else
    {
      if( pivindex1.value == 0 )
      {
        emit('on-load', 'Please Select PIV Type!', 1);
        return false;
      }
      else
      {
        if( pivindex2.value == 0 )
        {
          emit('on-load', 'Please Select PIV Name!', 1);
          return false;
        }
      }
    }

    return true;
  }

  const selectOption = (idx) => {
    pivindex.value = idx;
  }

  function ComboClicked(selectedPerson){
    pivindex.value = selectedPerson;
    initValues( true );
  }

  function ComboTableClicked(selectedPerson){
    pivindexTable.value = selectedPerson;
  }

  function updatecomboclicked( selectedPerson){    
    pivindex1.value = selectedPerson;
    initValues( false )
    pivindex2.value = 0;
  }

  function namecomboclicked( selectedPerson){
    
    var tempData = [];
    if( pivindex1.value == 1 )
        tempData = products; 
    else if( pivindex1.value == 2 )
      tempData = instruments;
    else if( pivindex1.value == 3 )
      tempData = vehciles;
    console.log(tempData)

    if( pivindex1.value != 3 )
    {
      quantity_upt.value = tempData[selectedPerson-1]['amount'];
      barcode_upt.value = tempData[selectedPerson-1]['barcode'];
    }
    else
    {
      targa_upt.value = tempData[selectedPerson-1]['targa'];
      owner_upt.value = tempData[selectedPerson-1]['username'];
    }
    name_upt.value = tempData[selectedPerson-1]['name'];
    comment_upt.value = tempData[selectedPerson-1]['comments'];

    piv_id.value = tempData[selectedPerson-1]['id'];
    pivindex2.value = selectedPerson;
  }
  
  function initValues( flag ){
    if( flag )
    {
      quantity_reg.value = 1;
      barcode_reg.value = '';
      name_reg.value = '';
      comment_reg.value = '';
      targa_reg.value = '';
      owner_reg.value = '';
    }
    else
    {
      quantity_upt.value = 1;
      barcode_upt.value = '';
      name_upt.value = '';
      comment_upt.value = '';
      targa_upt.value = '';
      owner_upt.value = '';
    }
  }
  
  function createJSON( database_id ){

    const jsonObject = {};
    jsonObject.databaseid = database_id;
    jsonObject.name = name_reg.value;

    if( database_id != 3 )
    {
      jsonObject.amount = quantity_reg.value;
      jsonObject.barcode = barcode_reg.value;
    }
    else
    {
      jsonObject.targa = targa_reg.value;
      jsonObject.username = owner_reg.value;
    }

    jsonObject.comments = comment_reg.value;
    jsonObject.mne = who;
    jsonObject.mnm = who;
    
    return jsonObject;
  }

  function createJSONUPT( database_id ){

    const jsonObject = {};
    jsonObject.pivid = piv_id.value;
    jsonObject.databaseid = database_id;
    jsonObject.name = name_upt.value;

    if( database_id != 3 )
    {
      jsonObject.amount = quantity_upt.value;
      jsonObject.barcode = barcode_upt.value;
    }
    else
    {
      jsonObject.targa = targa_upt.value;
      jsonObject.username = owner_upt.value;
    }

    jsonObject.comments = comment_upt.value;
    jsonObject.mne = who;
    jsonObject.mnm = who;

    return jsonObject;
  }

  async function registerWarehouse( objdata ) {
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    try {
        const response = await axios.post('/registerWarehouse', {
            data: {
                objdata,
            }
        },{
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
        });
        
        // Handle the response
        initValues( true )
        emit('on-load', 'Successfully Registered!', 2);
        
    } catch (error) {
        // Handle errors
        emit('on-load', 'OPPS! Please Refresh Page and Try Again!', 1);
        console.log(error);
    }
  }

  async function updateWarehouse( objdata ) {
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    try {
        const response = await axios.post('/updateWarehouse', {
            data: {
                objdata,
            }
        },{
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
        });
        
        // Handle the response
        initValues( false )
        emit('on-load', 'Successfully Updated!', 2);
        
    } catch (error) {
        // Handle errors
        //emit('on-load', 'OPPS! Please Refresh Page and Try Again!', 1);
        console.log(error);
    }
  }

  function createJSONEdit( post, flag ){

    const jsonObject = {};
    jsonObject.isAllowed = flag;
    jsonObject.pivid = post.value.pivid;
    jsonObject.databaseid = post.value.databaseid;
    jsonObject.warehoseid = post.value.id;
    jsonObject.requesterid = post.value.reqid;
    jsonObject.allowedNum = post.value.allowedNum;
    jsonObject.returnedNum = post.value.returnedNum;
    jsonObject.quantity = post.value.quantity;

    return jsonObject;
  }

  async function updateWarehouseReturned( objdata, post ) {
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    try {
        const response = await axios.post('/updateWarehouseEdit', {
            data: {
                objdata,
            }
        },{
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
        });
        
        // Handle the response
        if( post.value.returnedNum == post.value.quantity) 
          emit('on-load', post.value.title+" Returned Everything!", 2);
        
    } catch (error) {
        // Handle errors
        //emit('on-load', 'OPPS! Expired Key and Try Again!', 1);
        console.log(error);
    }
  }

  async function updateWarehouseAllowed( objdata, post ) {
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    try {
        const response = await axios.post('/updateWarehouseEdit', {
            data: {
                objdata,
            }
        },{
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
        });
        
        // Handle the response
        if( post.value.allowedNum == post.value.quantity && post.value.databaseid == 1 )
          emit('on-load', "Everything is sent to " + post.value.title, 2);
        
    } catch (error) {
        // Handle errors
        //emit('on-load', 'OPPS! Please Refresh Page and Try Again!', 1);
        // emit('on-load', 'OPPS! Expired Key and Try Again!', 1);
        console.log(error);
    }
  }


  async function deleteWarehouse() {
    
    try {
        const response = await axios.delete('/deleteWarehouse/'+pivindex1.value+":"+piv_id.value);
        
        emit('on-load', 'Successfully Deleted!', 2);
        // Trigger a page refresh
        //emit('handleTest');
    } catch (error) {
        console.error('Error deleting vacation:', error);
    }
  }

  $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip;   
  });

  </script>

  <style scoped>
  /* Set the width of the ul to 100% */
  .info-list {
    list-style: none;
    padding: 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 100%; /* Adjust the width as needed */
  }

  .info-list-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    border-bottom: 1px solid #ccc;
  }

  .right-button {
    background-color: #5ea8f7;
    color: #fff;
    border: none;
    padding: 5px 10px;
    margin-left: 5px;
    border-radius: 5px;
    cursor: pointer;
  }
  
  .right-button-red {
    background-color: #f356e6;
    color: #fff;
    border: none;
    padding: 5px 10px;
    margin-left: 5px;
    border-radius: 5px;
    cursor: pointer;
  }

  .table-container {
    max-height: 400px; /* Adjust the maximum height as needed */
    overflow: auto;
  }
  </style>
  