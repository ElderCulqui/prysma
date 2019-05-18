@extends('principal')

@section('contenido')
    <template v-if="menu==0">
        <h1>contenido 0</h1>
    </template>
    <template v-if="menu==1">
        <categoria></categoria>
    </template>
    <template v-if="menu==2">
        <producto></producto>
    </template>
    <template v-if="menu==3">
        <h1>contenido 3</h1>
    </template>
    <template v-if="menu==4">
        <proveedor></proveedor>
    </template>
    <template v-if="menu==5">
        <h1>contenido 5</h1>
    </template>
    <template v-if="menu==6">
        <cliente></cliente>
    </template>
    <template v-if="menu==7">
        <h1>contenido 7</h1>
    </template>
    <template v-if="menu==8">
        <rol></rol>
    </template>
@endsection