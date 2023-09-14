<template>
  <div>
    <DxDataGrid
        :data-source="dataSource"
        :show-borders="true"
    >
      <DxColumn data-field="message"/>
    </DxDataGrid>
  </div>
</template>

<script setup lang="ts">
import { createStore } from 'devextreme-aspnet-data-nojquery';
import {
  DxDataGrid,
  DxColumn,
} from 'devextreme-vue/data-grid';

const props = defineProps<{
  id: string
}>();

const url = '/api/messages/';

const dataSource = createStore({
  key: 'id',
  loadUrl: `${url}` + props.id
});


</script>
