import * as bcrypt from "bcryptjs";
import {
    Column,
    CreateDateColumn,
    Entity,
    ManyToOne,
    PrimaryGeneratedColumn,
    UpdateDateColumn,
} from "typeorm";
import { User } from "./User";

@Entity()
export class TaskSolution {
    @PrimaryGeneratedColumn("uuid")
    public id: string;

    @Column()
    public row: number;

    @Column()
    public col: string;

    @Column()
    public day: number;

    @ManyToOne(() => User, (user) => user.solutions)
    public user: User;
}
