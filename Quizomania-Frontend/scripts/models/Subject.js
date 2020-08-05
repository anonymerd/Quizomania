import * as Config from '../config.js';

export default class Subject {

    constructor(id) {
        this.id = id;
    }

    static async getAllSubjects(token) {

        try {
            const result = await fetch(`${Config.serverAddress}Subjects/read.php`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                }
            });

            return await result.json();

        } catch (error) {

            return error;
        }
    }
}