import './bootstrap';
import {createApp} from 'vue'

import Alpine from 'alpinejs'
import NavBar from "./components/layout/NavBar";
import CompactHeader from "./components/layout/CompactHeader";
import TweeterFeeder from "./components/lists/TweeterFeeder";
import DocumentCard from "./components/cards/DocumentCard";
import Footer from "./components/layout/Footer";
import DeviceList from "./components/lists/DeviceList"
import BrandIconStats from "./components/data-display/BrandIconStats"
import Table from "./components/table/Table"

const app = createApp({});

/* LAYOUT */
app.component('nav-bar', NavBar)
app.component('compact-header', CompactHeader)

/* CONTENT HOME PAGE */
app.component('tweeter-feeder', TweeterFeeder)
app.component('document-card', DocumentCard)
app.component('device-list', DeviceList)
app.component('brand-icon-stats', BrandIconStats)
app.component('users-table', Table)

app.mount('#app')
window.Alpine = Alpine

Alpine.start()
