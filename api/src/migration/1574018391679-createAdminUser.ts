import { getRepository, MigrationInterface } from "typeorm";
import { User } from "../entity/User";

export class createAdminUser1574018391679 implements MigrationInterface {
    public async up(): Promise<any> {
        const user = new User();
        user.nickname = "admin";
        user.password = "admin";
        user.isAdmin = true;
        user.hashPassword();
        const userRepository = getRepository(User);
        await userRepository.save(user);
    }

    public async down(): Promise<any> {
        //
    }
}
