import {createSlice} from "@reduxjs/toolkit";

const initialState = {
    user : JSON.parse(localStorage.getItem('user')) || false,
    token : localStorage.getItem('token') || false,
}

const authSlice = createSlice({
    name: 'auth',
    initialState,
    reducers : {
        login : (state,action) => {
            localStorage.setItem('user',JSON.stringify(action.payload.user))
            localStorage.setItem('token',action.payload.token)
            state.user = action.payload.user;
            state.token = action.payload.token;
        },
        logout : (state) => {
            localStorage.removeItem('user')
            localStorage.removeItem('token')
            state.user = false;
            state.token = false;
        },
    }
})

export const {login,logout} = authSlice.actions
export default authSlice.reducer
