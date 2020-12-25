import * as Config from '../config.js';

export default class Subject {
    constructor(id) {
        this.id = id;
    }

    static async getAllSubjects() {
        try {
            const result = await fetch(`${Config.serverAddress}Subjects/read.php`, {
                method: 'GET'
            });

            // console.log(result);
            return await result.json();
        } catch (error) {
            return error;
        }
    }
}