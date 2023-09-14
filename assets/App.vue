<template>
  <div id="data-grid-demo">
    <DxDataGrid
        id="gridContainer"
        :data-source="customDataSource"
        :remote-operations="true"
        :show-borders="true"
        key-expr="id"
    >
      <DxPaging :enabled="false"/>
      <DxPopup
          :show-title="true"
          :width="700"
          :height="525"
          title="Message"
      />
      <DxEditing
          :allow-updating="false"
          :allow-adding="true"
          :allow-deleting="false"
          mode="popup"
      >
        <DxPopup
            :show-title="true"
            :width="700"
            :height="225"
            title="Message"
        />
        <DxForm>
            <DxItem
                data-field="message"
                :width="170"
                :height="425"
                :col-span="2"
            />
        </DxForm>
      </DxEditing>

      <DxColumn data-field="id"/>
      <DxColumn data-field="date"/>
      <DxColumn
          :width="170"
          data-field="message"
      />
      <DxMasterDetail
          :enabled="true"
          template="master-detail"
      />
      <template #master-detail="{ data }">
        <Detail :id="data.key"/>
      </template>
    </DxDataGrid>
  </div>
</template>



<script setup lang="ts">
import {
  DxMasterDetail,
  DxDataGrid,
  DxColumn,
  DxPaging,
  DxEditing,
  DxPopup,
  DxForm,
  DxItem
} from 'devextreme-vue/data-grid';

import Detail from './Detail.vue';
import CustomStore from "devextreme/data/custom_store";

function handleErrors(response) {
  if (!response.ok) {
    throw Error(response.statusText);
  }
  return response;
}
const isNotEmpty = (value) => value !== undefined && value !== null && value !== '';

const customDataSource = new CustomStore({
  key: 'id',
  load: (loadOptions) => {
    let params = '?';

    [
      'sort',
    ].forEach(function(i) {
      if(i in loadOptions && isNotEmpty(loadOptions[i])) {
        params += `${i}=${JSON.stringify(loadOptions[i])}&`;
      }
    });
    params = params.slice(0, -1);

    return fetch(`api/messages${params}`)
        .then(handleErrors)
        .then(response => response.json())
        .then(response => {
          return {
            data: response.data,
            totalCount: response.totalCount
          };
        })
        .catch(() => { throw 'Network error' });
  },

  insert: (values) => {
    return fetch("api/messages", {
      method: "POST",
      body: JSON.stringify(values),
      headers: {
        'Content-Type': 'application/json'
      }
    })
        .then(handleErrors);
  }
});


</script>
