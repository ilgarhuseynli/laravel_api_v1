import {createSlice} from "@reduxjs/toolkit";


const initialState = {
    darkMode : JSON.parse(localStorage.getItem('darkMode')) || false,
    sidebarOpen : true,
}

const appSlice = createSlice({
    name: 'appSlice',
    initialState,
    reducers : {
        setSidebarOpen : (state,action) => {
            state.sidebarOpen = action.payload;
        },
        setDarkMode : (state,action) => {
            state.darkMode = action.payload;
        },
        toggle : (state) => {
            let payload = !state.darkMode;
            localStorage.setItem('darkMode',JSON.stringify(payload))
            state.darkMode = payload;
        },
    }
})

export const {setSidebarOpen,setDarkMode,toggle} = appSlice.actions
export default appSlice.reducer
