import axios from "axios";
import {getToken} from "../utils/GetJWT";
import {makeAutoObservable} from "mobx";

export interface token {
    token: string;
}

class TokenStore {
    public token: string = ''

    constructor() {
        makeAutoObservable(this)
    }

    public fetchToken = () => {
        //this.token = getToken()
        console.log('token', this.token)
    }
}

export default new TokenStore()